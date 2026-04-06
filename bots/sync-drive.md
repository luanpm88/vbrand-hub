# Bot: Sync Drive — Đẩy files lên Google Drive

Sync thư mục `docs/drive_shared/` lên Google Drive folder `vbrand-docs/`.

## Setup (1 lần)

```bash
# 1. Cài rclone
brew install rclone

# 2. Cấu hình Google Drive (mở browser login luanpm88@gmail.com)
rclone config
# → n → gdrive → drive → Enter → Enter → 1 → Enter → n → y → login → n → y

# 3. Test
rclone lsd luanpm88:
```

## Cách dùng

```bash
# Sync tất cả files trong drive_shared/ lên Google Drive
bots/sync-drive.md

# Hoặc chạy trực tiếp
rclone sync docs/drive_shared/ luanpm88:vBrand_Shared/SGCONNECT/ --progress
```

## Khi user nói ngắn gọn

| User nói | Claude làm |
|----------|-----------|
| `sync drive` | Chạy rclone sync |
| `đẩy pdf lên drive` | Copy PDF mới vào drive_shared/ → sync |
| `update drive` | Sync drive_shared/ lên Google Drive |

## Các bước thực hiện

### Bước 1: Kiểm tra rclone config

```bash
rclone listremotes
```

Nếu không có `luanpm88:` → hướng dẫn user chạy `rclone config` (xem Setup ở trên).

### Bước 2: Sync lên Google Drive

```bash
rclone sync docs/drive_shared/ luanpm88:vBrand_Shared/SGCONNECT/ --progress
```

### Bước 3: Lấy share link (nếu cần)

```bash
# List files đã upload
rclone ls luanpm88:vBrand_Shared/SGCONNECT/

# Lấy link share (public)
rclone link luanpm88:vBrand_Shared/SGCONNECT/SALES_HANDOVER_v1.pdf
rclone link luanpm88:vBrand_Shared/SGCONNECT/USER_GUIDE_v1.pdf
```

## Quy trình update PDF + sync

Khi cần update tài liệu:

1. Sửa file `.md` trong `docs/`
2. Xuất PDF: `cd docs && npx md-to-pdf SALES_HANDOVER.md && npx md-to-pdf USER_GUIDE.md`
3. Copy vào drive_shared (tăng version):
   ```bash
   cp docs/SALES_HANDOVER.pdf docs/drive_shared/SALES_HANDOVER_v2.pdf
   cp docs/USER_GUIDE.pdf docs/drive_shared/USER_GUIDE_v2.pdf
   ```
4. Sync: `rclone sync docs/drive_shared/ luanpm88:vBrand_Shared/SGCONNECT/ --progress`

## Files hiện có trong drive_shared/

| File | Version | Date |
|------|---------|------|
| SALES_HANDOVER_v1.pdf | v1 | 2026-04-06 |
| USER_GUIDE_v1.pdf | v1 | 2026-04-06 |
