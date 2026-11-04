# Laravel demo platform

One Laravel installation serves multiple independent marathon demos. CRO Trail is at `/cro-trail/`; a second fictional example is at `/coast-run/`. The root does not list them. Every public demo page has `noindex, nofollow`, and `public/robots.txt` blocks all crawlers.

## How a demo is packaged

Each demo lives under `demos/{slug}/`:

```text
demos/cro-trail/
  manifest.php          name, pages, races, test prices, registration rules
  views/                custom Blade layout and pages
  lang/it/messages.php  Italian copy
  lang/en/messages.php  English copy
  assets/               CSS, JavaScript, images and fonts
```

The URL segment is accepted only when a matching directory and manifest exist and its name uses lowercase letters, numbers or hyphens. Generic Laravel routes resolve pages, races, registration, checkout and tickets under `/{slug}/...`; new demos do not add routes or controllers. Assets are served through `/{slug}/assets/{path}` by a Laravel controller. It allows only common web asset extensions, verifies the real file stays inside that demo's `assets/` directory, and returns `ETag` and `Cache-Control` headers. The source directory remains outside the Nginx document root.

The same controllers, models, test Stripe checkout, webhook and `/admin` work for every demo. `BelongsToDemo` applies a global scope to all demo data models and writes the current `demo_slug` on creation. The public route, admin selector or verified webhook metadata establishes the context before a model query. Order and ticket URLs cannot cross demos. Medical documents are private files under `storage/app/private/demo-documents/{slug}/`; exports are generated on demand, so neither has a separate database table.

## Add a demo

1. Run `php artisan demo:new nome-demo` locally. The command validates the slug and creates a working sample with two races, IT/EN pages, views and CSS.
2. Ask Codex to customize **only** `demos/nome-demo/`: replace copy, images, design, race/pricing configuration and privacy URL. Keep the shared route names and registration partial.
3. Test `/{slug}/`, `/{slug}/en`, `/{slug}/contatti`, race and registration pages, and test checkout.
4. Commit and `git push` locally. On the VPS run `git pull` and `./deploy.sh` in the existing project directory. No new VPS, Nginx site, route file or asset build is needed.

The registry reads manifests from disk at runtime. It does not depend on cached Laravel config or routes. The deploy script clears route, config and Blade view caches and reloads PHP-FPM so updated PHP/Blade files and OPcache are refreshed. It runs `composer install --no-dev --optimize-autoloader` only when `composer.lock` changed since the last successful deploy or `vendor/autoload.php` is missing; `php artisan migrate --force` runs safely even when there are no migrations.

## First VPS setup

- Configure one Nginx virtual host with its `root` set to this project's `public/`, PHP 8.4-FPM, and the standard Laravel fallback `try_files $uri $uri/ /index.php?$query_string;`. Keep `demos/`, `.env` and `storage/` outside the public document root. The existing static `/presentation/` and `/proposta-commerciale/` folders are separate from demo URLs.

  Minimal routing portion (adapt the domain, paths and TLS configuration):

  ```nginx
  server {
      server_name demo.example.com;
      root /var/www/demo-platform/public;
      index index.php;

      location / { try_files $uri $uri/ /index.php?$query_string; }
      location ~ \.php$ {
          include snippets/fastcgi-php.conf;
          fastcgi_pass unix:/run/php/php8.4-fpm.sock;
      }
      location ~ /\.(?!well-known).* { deny all; }
  }
  ```
- Configure `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-demo-domain`, a database connection, and `DEMO_STRIPE_KEY=pk_test_...`, `DEMO_STRIPE_SECRET=sk_test_...`, `DEMO_STRIPE_WEBHOOK_SECRET=whsec_...`. The application refuses checkout unless both Stripe keys have test prefixes. Register one test webhook endpoint at `https://your-demo-domain/webhook/stripe` for `payment_intent.succeeded`. The signed webhook resolves the demo from `demo_slug` and `order_id` metadata and is idempotent.
- Set `DEMO_DATA_RETENTION_DAYS=30` (or another positive number). Outbound participant email is disabled in code, and Laravel's mail driver is forced to `log`; an SMTP/Brevo value in `.env` cannot activate sending.
- Run `./deploy.sh` once after the initial pull. The VPS account needs permission to reload `php8.4-fpm` via `sudo -n systemctl reload php8.4-fpm` (or run the script as root). Create the admin account with `php artisan demo:admin you@example.com`; the command prompts for a password. Sign in at `/admin/login`.
- Add this cron entry, replacing the project path and PHP executable if needed:

```cron
* * * * * cd /var/www/demo-platform && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

Laravel schedules `demo:cleanup` daily at 03:00 Europe/Rome. The command removes test orders older than the retention period, their tickets, and associated private document files, per demo. Run `php artisan demo:cleanup --days=30` manually when needed.

## Local verification

```bash
php artisan migrate
php artisan test
php artisan serve --host=127.0.0.1 --port=8765
```

The tests cover two distinct designs and prices, IT/EN URLs, new demo discovery, admin and public data isolation, private uploads, invalid slugs and asset traversal, test-only payment guards, webhook signatures and idempotency, and cleanup. Payment API calls are mocked in tests; a full card payment requires valid Stripe **test** credentials and a test card in the browser.
