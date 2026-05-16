# Prayer Times — Masjid Al-Hidayah TV Display

A full-screen TV display app showing prayer times, poster carousel, and du'aa. Built with PHP/Fat-Free Framework, MySQL, and Bootstrap 5.

---

## Stack

- PHP 8.1+
- Fat-Free Framework 3.9
- MySQL
- Bootstrap 5.3
- Apache + mod_rewrite

---

## Local Development

### Requirements

- PHP 8.1+
- MySQL
- Apache with `mod_rewrite` (Homebrew recommended on macOS)

### Setup

**1. Clone the repo**
```bash
git clone git@github.com:abudayah/prayer-times.git
cd prayer-times
```

**2. Create your `.env` file**
```bash
cp .env.example .env
```
Edit `.env` with your local DB credentials:
```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=prayer_times
DB_USER=root
DB_PASS=

APP_ENV=local
APP_BASE_PATH=/prayer-times/
APP_VERSION=1.0.0
```

**3. Create the database**
```bash
mysql -u root -e "CREATE DATABASE prayer_times;"
mysql -u root prayer_times < prayer_times.sql
```

**4. Dependencies are committed — no composer install needed**

Vendor packages are committed to the repo for reliable deployment.

**5. Configure Apache**

Set `DocumentRoot` to your `www` directory and ensure `AllowOverride All` is set. The app runs at:
```
http://localhost:8080/prayer-times/tv/main   ← TV display
http://localhost:8080/prayer-times/admin     ← Admin panel
http://localhost:8080/prayer-times/login     ← Login
```

---

## Production Deployment (cPanel Git)

### First-time setup

**1. In cPanel → Git Version Control**, add the repo and set the deployment path to:
```
/home2/theisbcc/public_html/prayer-times
```

**2. Create `.env` on the server** (never committed to git):
```bash
cp /home2/theisbcc/public_html/prayer-times/.env.example \
   /home2/theisbcc/public_html/prayer-times/.env
nano /home2/theisbcc/public_html/prayer-times/.env
```
Fill in production DB credentials and set `APP_ENV=production`.

**3. Run the DB migration** if the database is new:
```bash
mysql -u <user> -p <dbname> < /home2/theisbcc/public_html/prayer-times/prayer_times.sql
```

If the DB already exists and you only need to add the `published` column:
```sql
ALTER TABLE posters ADD COLUMN published tinyint(1) NOT NULL DEFAULT 1 AFTER file_path;
```

### Deploying updates

```bash
# Local
git add -A
git commit -m "your message"
git push

# On server
cd /home2/theisbcc/public_html/prayer-times
git pull
```

The `.cpanel.yml` runs automatically on each deploy to create/chmod the `uploads/` directory.

### Force-reload all TV screens

Bump `APP_VERSION` in the server's `.env` (e.g. `1.0.0` → `1.0.1`). All TV screens will reload within 15 minutes on their next poll cycle.

---

## Admin Panel

| URL | Description |
|-----|-------------|
| `/login` | Login with username/password |
| `/admin` | Upload, publish/unpublish, download, delete posters |
| `/logout` | Ends session |

**Poster requirements:** A4 portrait ratio (1:1.4142), minimum 700px wide.

---

## Environment Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `DB_HOST` | MySQL host | `localhost` |
| `DB_PORT` | MySQL port | `3306` |
| `DB_NAME` | Database name | `prayer_times` |
| `DB_USER` | Database user | `root` |
| `DB_PASS` | Database password | |
| `APP_ENV` | Environment | `local` or `production` |
| `APP_BASE_PATH` | URL base path (with trailing slash) | `/prayer-times/` |
| `APP_VERSION` | App version — bump to force TV reload | `1.0.0` |

---

## Project Structure

```
prayer-times/
├── assets/
│   ├── css/style.css          # Main stylesheet (TV layout)
│   ├── images/                # Static images
│   └── js/                    # Frontend scripts
├── data/
│   └── prayers_schedule_data.csv
├── src/
│   ├── configs/
│   │   ├── config.cfg         # App settings
│   │   └── routes.cfg         # URL routes
│   ├── controllers/
│   │   ├── AdminController.php
│   │   └── MainTvController.php
│   ├── models/
│   │   └── AdminModel.php
│   └── views/
│       ├── html/              # HTML templates (Fat-Free)
│       ├── HomeView.php
│       └── MainTvView.php
├── uploads/                   # Uploaded posters (not in git)
├── vendor/                    # Committed for reliable deployment
├── .env                       # Local secrets (not in git)
├── .env.example               # Template for .env
├── .cpanel.yml                # cPanel auto-deploy tasks
├── composer.json
├── index.php                  # App entry point
└── prayer_times.sql           # Database schema + seed data
```
