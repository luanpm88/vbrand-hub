# Bot: Deploy Mobile — Commit + Push

Fix bug React Native app, commit + push lên remote. Không build/deploy (EAS build riêng).

## Cách dùng

```
bots/automated/deploy-mobile.md
```

## Git remote

- `origin` → `git@github.com:luanpm88/vbrand-mobile.git`
- Branch: `main`

## Flow

### Bước 1: Check status

```bash
cd /Users/luan/apps/vbrand/mobile
echo "=== Branch: $(git branch --show-current) ==="
git status --short
echo "=== Recent commits ==="
git log --oneline -5
```

### Bước 2: Verify changes

Kiểm tra có uncommitted changes không:

```bash
cd /Users/luan/apps/vbrand/mobile
git diff --stat
```

Nếu không có changes → báo "Không có thay đổi để commit." và DỪNG.

### Bước 3: Commit + Push

```bash
cd /Users/luan/apps/vbrand/mobile
git add <specific-files>
git commit -m "fix: <mô tả> (fixes #N)"
git push origin main
```

### Output

```
✅ Mobile fix committed + pushed!
- Branch: main
- Commit: <hash> — <message>
- Pushed to: origin/main (luanpm88/vbrand-mobile)
- Committed at: <timestamp>
- ⚠️ Cần EAS build riêng để deploy lên store
```

## Lưu ý

- **KHÔNG deploy**: Mobile app cần EAS build (`eas build`) riêng
- Push lên remote để lưu trên cloud
- Chỉ commit code fix, user tự build khi sẵn sàng
