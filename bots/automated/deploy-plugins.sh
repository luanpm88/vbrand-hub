#!/usr/bin/env bash
#
# deploy-plugins.sh — deploy the acelle/brand AND acelle/messenger plugins to
# the BrandViet production app (app.sgconnect.vn, vbrand@54.169.34.13).
#
# Run from your local Mac. Pushes plugin source → prod, then registers /
# activates / migrates / publishes each plugin idempotently. Safe to re-run.
#
#   bash ~/apps/vbrand/bots/automated/deploy-plugins.sh            # both plugins
#   bash ~/apps/vbrand/bots/automated/deploy-plugins.sh messenger  # one plugin
#   bash ~/apps/vbrand/bots/automated/deploy-plugins.sh brand
#
# WHY a full-plugin rsync (not single files):
#   2026-06-09 incident — rsyncing ONE messenger ServiceProvider file across a
#   version skew fataled `php artisan` (a referenced class existed locally but
#   not on prod). Deploy the WHOLE plugin so the code + classes + migrations are
#   internally consistent. `--delete` removes files refactored away locally so
#   prod has no stale dead code.
#
# WHAT IS PRESERVED on prod: each plugin's own vendor/ dir (excluded from the
#   rsync — prod keeps its proven, installed deps). If a plugin adds a NEW
#   composer dependency, install it on prod separately before deploying.
set -euo pipefail

SSH="vbrand@54.169.34.13"
PROD_PLUGINS="/home/vbrand/app-new/storage/app/plugins/acelle"
PROD_APP="/home/vbrand/app"

# plugin-name → local source repo (case, not assoc-array — macOS ships bash 3.2)
src_for() {
  case "$1" in
    brand)     echo "/Users/luan/apps/acelle_brand" ;;
    messenger) echo "/Users/luan/apps/acelle_messenger" ;;
    *)         echo "" ;;
  esac
}

# Files that must never ship into a plugin dir on prod.
#  - vendor      : keep prod's installed deps
#  - vbrandsync  : a SYMLINK inside acelle_brand → the WP plugin; not part of the
#                  acelle plugin, deploys to WP sites separately (see deploy-sites.md)
RSYNC_EXCLUDES=(
  --exclude '/vendor' --exclude '/.git' --exclude '/node_modules'
  --exclude '/vbrandsync' --exclude '/docs' --exclude '/tests'
  --exclude '.DS_Store' --exclude '.phpunit.result.cache' --exclude '/.github'
)

deploy_one() {
  local name="$1"
  local src; src="$(src_for "$name")"
  [ -n "$src" ] || { echo "✗ unknown plugin '$name'"; exit 1; }
  [ -d "$src" ] || { echo "✗ source not found: $src"; exit 1; }
  local dst="$PROD_PLUGINS/$name"

  echo "──────────────────────────────────────────────"
  echo "▶ Deploying acelle/$name  ($src)"
  echo "──────────────────────────────────────────────"

  # 1) Backup the live prod plugin dir (keep only the latest).
  ssh -o BatchMode=yes "$SSH" "
    set -e; cd '$PROD_PLUGINS';
    rm -rf '$name'.bak-* 2>/dev/null || true;
    cp -a '$name' '$name'.bak-\$(date +%Y%m%d-%H%M%S);
    echo '  backup: '\$(ls -d '$name'.bak-*)"

  # 2) Push source (full plugin, prod's vendor preserved).
  rsync -rlptz --delete "${RSYNC_EXCLUDES[@]}" "$src/" "$SSH:$dst/"

  # 3) Idempotent register + publish + migrate + activate.
  ssh -o BatchMode=yes "$SSH" "cd '$PROD_APP' && php artisan tinker --execute='
    \$name = \"acelle/$name\";
    \$p = \App\Model\Plugin::firstOrNew([\"name\" => \$name]);
    \$p->syncMetadataFromComposer();
    \$fresh = ! \$p->exists;
    if (\$fresh) { \$p->status = \App\Model\Plugin::STATUS_INACTIVE; }
    \$p->save();
    \$p->load(true);                                   // boot the ServiceProvider
    \Artisan::call(\"vendor:publish\", [\"--force\" => true, \"--tag\" => \"plugin\"]);
    \Artisan::call(\"migrate\", [\"--path\" => \"storage/app/plugins/\".\$name.\"/database/migrations\", \"--force\" => true]);
    if (\$p->status !== \App\Model\Plugin::STATUS_ACTIVE) { \$p->activate(); }
    echo \"  acelle/$name -> \".\$p->status.PHP_EOL;
  ' 2>&1 | grep -vE 'Psy|deprecated'"
}

TARGETS=("$@")
[ ${#TARGETS[@]} -gt 0 ] || TARGETS=(brand messenger)
for t in "${TARGETS[@]}"; do deploy_one "$t"; done

# 4) Refresh caches once at the end.
echo "▶ Refreshing caches"
ssh -o BatchMode=yes "$SSH" "cd '$PROD_APP' && php artisan config:cache >/dev/null && php artisan view:clear >/dev/null && php artisan route:clear >/dev/null && echo '  caches refreshed'"

# 5) Verify.
echo "▶ Verifying prod"
ssh -o BatchMode=yes "$SSH" "cd '$PROD_APP' && echo -n '  artisan: ' && php artisan --version && echo '  plugins:' && php artisan tinker --execute='foreach(\DB::table(\"plugins\")->get() as \$p){echo \"    \".\$p->name.\" | \".\$p->status.PHP_EOL;}' 2>&1 | grep acelle/"
for u in /login /rui/messenger/inbox; do
  code=$(curl -s -o /dev/null -w '%{http_code}' -k "https://app.sgconnect.vn$u")
  echo "  https://app.sgconnect.vn$u -> $code"
done
echo "✅ Done. Rollback a plugin: ssh $SSH 'cd $PROD_PLUGINS && rm -rf <name> && mv <name>.bak-* <name>'"
