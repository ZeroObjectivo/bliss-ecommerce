# Bliss Website

Bliss is a PHP MVC ecommerce website built to run locally on XAMPP.

## What Teammates Need

- XAMPP with Apache and MySQL running
- PHP with `pdo_mysql`
- Apache `mod_rewrite` enabled
- The project placed at `xampp/htdocs/php/Webdev`

The current codebase uses hardcoded `/php/Webdev/public` URLs across controllers, views, JavaScript, and stored database links. For now, teammates should keep the same local folder path.

## Quick Setup

1. Clone or copy this repository into:

```text
D:\xampp\htdocs\php\Webdev
```

2. Start Apache and MySQL in XAMPP.

3. Import the public demo seed:

Option A: phpMyAdmin

- Open `http://localhost/phpmyadmin`
- Import [database/bliss_ecommerce_seed_public.sql](database/bliss_ecommerce_seed_public.sql)

Option B: MySQL CLI from PowerShell

```powershell
& "D:\xampp\mysql\bin\mysql.exe" -u root --execute="source D:/xampp/htdocs/php/Webdev/database/bliss_ecommerce_seed_public.sql"
```

4. Confirm these folders are writable by Apache/PHP:

- `public/uploads/`
- `public/uploads/profiles/`

5. Open the site:

```text
http://localhost/php/Webdev/public/
```

## Demo Accounts

All seeded accounts use the same password:

```
password
```

- Super Admin: `superadmin@bliss.test`
- Admin: `admin@bliss.test`
- Customer: `john@bliss.test`

## Database Notes

- The app connects through [app/core/Database.php](app/core/Database.php).
- Default local database settings are:
  - host: `127.0.0.1`
  - user: `root`
  - password: empty
  - database: `bliss_ecommerce`
- If a teammate uses different MySQL credentials, they need to update [app/core/Database.php](app/core/Database.php).
- The app auto-creates the database name, but it does not build the full production schema on its own. The SQL seed is still required.

## Shared Repo Rules

- Commit [database/bliss_ecommerce_seed_public.sql](database/bliss_ecommerce_seed_public.sql) for team onboarding.
- Do not commit the raw MySQL data folder `bliss_ecommerce/`.
- Do not commit the private live dump `database/bliss_ecommerce_seed.sql`.
- Product demo images in `public/uploads/` are part of the shared repo state.
- User avatar uploads in `public/uploads/profiles/` are intentionally ignored.

## Before Real Deployment

This repo is ready for team sharing on GitHub after review of tracked files, but it is not yet deployment-ready for arbitrary paths or hosting. The main portability issue is the hardcoded `/php/Webdev/public` base path.
