# PHP Native (Apache) Starter

Template starter untuk aplikasi PHP klasik (tanpa framework) di [FlazHost Runner](https://flazhost.com/runner). Dockerfile sudah siap pakai dengan PHP 8.2-apache + ekstensi umum.

## Yang sudah disediakan

| File | Fungsi |
|---|---|
| `Dockerfile` | PHP 8.2-apache + `mysqli`, `pdo_mysql`, `gd`, `zip`, `mbstring`, `intl` + mod_rewrite + AllowOverride All |
| `docker-entrypoint.sh` | Auto-set Apache listen port sesuai env `PORT` (di-inject runner) |
| `captain-definition` | Manifest CapRover (point ke Dockerfile) |
| `.github/workflows/deploy.yml` | CI: build image → push ke `registry.flazhost.com` → deploy ke runner |
| `.dockerignore` | Exclude `.env`, `.git`, `node_modules`, dll dari image |
| `.env.example` | Template variable lingkungan |
| `index.php` | Demo halaman home (info runtime + DB connectivity test). Replace dengan kode Anda. |

## Cara pakai

### Opsi 1: Deploy via FlazHost Runner (recommended)

1. Login ke [flazhost.com](https://flazhost.com) → Runner → New App
2. Pilih template **PHP Native (Apache)**
3. Connect repo Anda (yang fork/clone dari starter ini) atau biarkan starter ini sebagai base
4. Optional: link MySQL service → env `DB_*` auto-fill
5. Deploy → runner clone, build Dockerfile, push image, deploy container
6. Otomatis kena domain `<app>.apps.flazhost.com` (atau custom domain)

### Opsi 2: Local development

```bash
git clone https://github.com/flazhost-runner/php-native-apache.git my-app
cd my-app
cp .env.example .env
docker build -t my-app .
docker run -p 8080:80 --env-file .env my-app
# Buka http://localhost:8080
```

## Customize

### Tambah ekstensi PHP

Edit `Dockerfile`, tambahkan ke baris `docker-php-ext-install`:

```dockerfile
RUN apt-get install -y libxml2-dev libsodium-dev \
 && docker-php-ext-install soap sodium
```

### Ganti DocumentRoot (Apache)

Default: `/var/www/html`. Untuk subfolder (mis. `/public`):

```dockerfile
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf
```

### Persistence file

Mount volume di App Settings → Volumes:

| Mount path | Use case |
|---|---|
| `/var/www/html/uploads` | Upload user (gambar, dokumen) |
| `/var/www/html/storage` | Cache, session, log |

### CI/CD secrets

Workflow otomatis pakai secrets yang di-inject saat **Setup CD** di /admin/paas:

- `HARBOR_USERNAME`, `HARBOR_PASSWORD` — push ke registry FlazHost
- `CAPROVER_SERVER`, `CAPROVER_APP_TOKEN` — deploy ke runner
- Vars: `HARBOR_PROJECT`, `CAPROVER_APP`

Tidak perlu set manual — runner auto-injects setelah create app.

## License

MIT
