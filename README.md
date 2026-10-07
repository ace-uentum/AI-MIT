<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Sample Project

A Laravel 13 application with an authenticated **AI Delay-Risk Dashboard**, built with Livewire Flux, Alpine.js, Tailwind CSS v4 and Vite.

---

## 1. Requirements

Install the following on your local machine before you start.

| Tool | Minimum version | Notes |
| --- | --- | --- |
| **PHP** | 8.3 (8.5 recommended) | CLI build |
| **Composer** | 2.x | PHP dependency manager |
| **Node.js** | 20 LTS (24.x recommended) | Ships with npm |
| **npm** | 10.x | Installed with Node.js |
| **Git** | 2.x | To clone the repository |
| **SQLite** | 3.x | Default database driver |

### Required PHP extensions

```
bcmath, ctype, curl, dom, fileinfo, json, mbstring, openssl, pcre,
pdo, pdo_sqlite, sqlite3, tokenizer, xml, zip
```

Verify your environment:

```bash
php -v
php -m          # confirm the extensions above are listed
composer -V
node -v
npm -v
git --version
```

### Installing the prerequisites

<details>
<summary><strong>macOS (Homebrew)</strong></summary>

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
brew install php composer node git sqlite
```
</details>

<details>
<summary><strong>Ubuntu / Debian</strong></summary>

```bash
sudo add-apt-repository ppa:ondrej/php && sudo apt update
sudo apt install -y php8.3-cli php8.3-mbstring php8.3-xml php8.3-curl \
    php8.3-zip php8.3-sqlite3 php8.3-bcmath unzip git sqlite3

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```
</details>

<details>
<summary><strong>Windows</strong></summary>

Use [Laravel Herd](https://herd.laravel.com/windows) (bundles PHP + Composer), install
[Node.js LTS](https://nodejs.org/) and [Git for Windows](https://git-scm.com/download/win).
Alternatively run everything inside WSL2 and follow the Ubuntu instructions.
</details>

---

## 2. Setup from scratch

### Step 1 — Configure Git (first time on a new machine)

```bash
git config --global user.name  "Your Name"
git config --global user.email "you@example.com"
```

Authenticate with GitHub using **either** option:

```bash
# Option A - SSH (recommended)
ssh-keygen -t ed25519 -C "you@example.com"
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_ed25519
cat ~/.ssh/id_ed25519.pub   # add this key at https://github.com/settings/keys
ssh -T git@github.com       # verify

# Option B - GitHub CLI over HTTPS
gh auth login
```

### Step 2 — Clone the repository

```bash
# SSH
git clone git@github.com:<your-org>/sample-project.git

# or HTTPS
git clone https://github.com/<your-org>/sample-project.git

cd sample-project
```

### Step 3 — Install PHP dependencies

```bash
composer install
```

### Step 4 — Install JavaScript dependencies

```bash
npm install
```

### Step 5 — Create the environment file

```bash
cp .env.example .env
```

Open `.env` and review these keys:

| Key | Purpose |
| --- | --- |
| `APP_NAME` | Display name of the app |
| `APP_URL` | Base URL, defaults to `http://localhost:8000` |
| `DB_CONNECTION` | `sqlite` by default — no database server needed |
| `ADMIN_NAME` / `ADMIN_EMAIL` / `ADMIN_PASSWORD` | Credentials for the seeded admin user |

### Step 6 — Generate the application key

```bash
php artisan key:generate
```

This fills in `APP_KEY` in your `.env`. The app will not boot without it.

### Step 7 — Create the database and run migrations

```bash
touch database/database.sqlite
php artisan migrate
```

> **Using MySQL instead?** Create a database, then set `DB_CONNECTION=mysql`,
> `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` in `.env`
> before running `php artisan migrate`.

### Step 8 — Seed the admin account

```bash
php artisan db:seed
```

Creates the login user from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in your `.env`
(defaults: `admin@example.com` / `password`).

### Step 9 — Link storage and build assets

```bash
php artisan storage:link
npm run build
```

### Step 10 — Run the application

```bash
composer run dev
```

This starts the PHP server, queue worker, log viewer and Vite together.
Visit **http://localhost:8000** and sign in with the seeded admin credentials.

Prefer separate terminals? Run:

```bash
php artisan serve     # terminal 1
npm run dev           # terminal 2
```

---

## 3. One-command setup (shortcut)

After cloning and installing the prerequisites, steps 3–9 can be replaced with:

```bash
composer run setup
```

Then seed and run:

```bash
php artisan db:seed
composer run dev
```

---

## 4. Everyday commands

| Command | Description |
| --- | --- |
| `composer run dev` | Serve app + queue + logs + Vite |
| `composer run test` | Clear config and run the PHPUnit suite |
| `php artisan migrate:fresh --seed` | Rebuild the database from scratch |
| `php artisan tinker` | Interactive REPL |
| `php artisan pail` | Tail application logs |
| `vendor/bin/pint` | Format PHP code |
| `npm run build` | Production asset build |

---

## 5. Troubleshooting

| Problem | Fix |
| --- | --- |
| `No application encryption key has been specified` | Run `php artisan key:generate` |
| `database file does not exist` | Run `touch database/database.sqlite` then `php artisan migrate` |
| `could not find driver` | Enable the `pdo_sqlite` (or `pdo_mysql`) PHP extension |
| Styles missing / 404 on assets | Run `npm run build`, or keep `npm run dev` running |
| Permission denied on logs or cache | `chmod -R 775 storage bootstrap/cache` |
| Stale config after editing `.env` | `php artisan config:clear` |
| Port 8000 already in use | `php artisan serve --port=8080` |

---

## 6. Project structure

```
app/            Controllers, models, application logic
config/         Configuration files
database/       Migrations, factories, seeders, database.sqlite
design-system/  Design system assets
public/         Web root and compiled assets
resources/      Blade views, CSS and JS sources
routes/         web.php and console.php
tests/          Feature and unit tests
```

---

## License

Open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
