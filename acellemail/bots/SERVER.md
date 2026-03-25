# AcelleMail Landing — Server Configuration

## Server

| Key | Value |
|-----|-------|
| Host | `brandnew` (SSH config) |
| IP | `18.141.199.175` |
| SSH User | `ubuntu` (sudoer) |
| OS | Ubuntu 24.04 LTS |
| Web Server | Nginx 1.24 |
| PHP | 8.3.6 (FPM) |
| SSL | Let's Encrypt (certbot 5.4, auto-renew) |

## URL

| URL | Description |
|-----|-------------|
| https://beta.acellemail.com | Landing site (public) |

## Paths

| Path | Description |
|------|-------------|
| `/var/www/acellemail-landing/` | Site root (Nginx document root) |
| `/etc/nginx/sites-available/beta.acellemail.com` | Nginx vhost |
| `/etc/letsencrypt/live/beta.acellemail.com/` | SSL cert |

## PHP-FPM

| Key | Value |
|-----|-------|
| FPM User | `vbrandwww` |
| FPM Group | `vbrand` |
| Socket | `/var/run/php/php8.3-fpm.vbrand.sock` |

File ownership must be `vbrandwww:vbrand`.

## Nginx Config

File: `/etc/nginx/sites-available/beta.acellemail.com`

```nginx
server {
    server_name beta.acellemail.com;
    root /var/www/acellemail-landing;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.vbrand.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # SSL managed by certbot
    listen 443 ssl;
    ssl_certificate /etc/letsencrypt/live/beta.acellemail.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/beta.acellemail.com/privkey.pem;
}
```

## SSL Certificate

- Provider: Let's Encrypt
- Expires: 2026-06-22
- Auto-renew: Yes (certbot systemd timer)

## No Database

This is a static PHP site — no database required.

## Common Operations

```bash
# Check site
curl -sI https://beta.acellemail.com

# Check Nginx error log
ssh brandnew "sudo tail -50 /var/log/nginx/error.log"

# Restart PHP-FPM
ssh brandnew "sudo systemctl restart php8.3-fpm"

# Restart Nginx
ssh brandnew "sudo systemctl reload nginx"

# Renew SSL
ssh brandnew "sudo certbot renew"
```
