<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Deployment & Migrations

On the OLA server, migrations do **not** run as part of the GitHub Actions workflow. They run inside the `init` service defined in [frankenphp_server/docker-compose-traefik.yml](frankenphp_server/docker-compose-traefik.yml), via [frankenphp_server/init.sh](frankenphp_server/init.sh):

1. `./pha start` (invoked by the `Start services` step in [.github/workflows/ola_server.yml](.github/workflows/ola_server.yml)) brings up `docker-compose-traefik.yml`.
2. The `init` container runs first — it installs Composer dependencies, then runs `php artisan migrate --force`, links storage, and builds frontend assets.
3. The `frankenphp` app service has `depends_on: init: condition: service_completed_successfully`, so it only starts once migrations and the build have finished successfully.
4. If `init` fails (including a failed migration), Compose does not start `frankenphp`, and the deploy leaves the previous app container running until the next successful deploy.

Because `init` shares the same code volume and `.env` as `frankenphp`, running `--force` is safe here: there's no interactive prompt to worry about, and a failed migration blocks the new app version from going live rather than partially deploying.

To run a migration manually on the server (e.g. outside a deploy):

```bash
ssh admin@<OLA_IP> -i ~/.ssh/runpodKey
cd ~/simple-expense
docker compose -f frankenphp_server/docker-compose-traefik.yml --env-file frankenphp_server/.env run --rm init
```

### Measuring deploy downtime

Run this from your laptop while triggering a deploy (push a commit with `[ola]` in the message) to see exactly how much downtime, if any, occurs during the container swap:

```bash
./frankenphp_server/scripts/watch-uptime.sh https://expense.manage.ourladyofapostles.edu.gh/up 180
```

Any non-`200` lines in the output mark the downtime window. With the recreate-with-overlap deploy (see above), this should show at most a few seconds of gap around the final container swap — not the multi-minute gap from the old stop-then-rebuild flow.

## Server-to-Server Migration

To move data from one running instance of this app (the **old server**) to another (the **new server**), use the built-in pull-based migration feature — a full DB-to-DB migration isn't needed; both servers just need to be reachable over HTTP.

### Setup

1. On **both** servers, set the same `MIGRATION_SECRET` value in `.env` (see `.env.example`). If either server has it blank, the feature refuses to run on that side.
2. Make sure the old server's `/server-migration/export` endpoint is reachable from the new server (network/firewall permitting).

### Running a migration

1. On the **new** server, visit `/server-migration`.
2. Enter the old server's base URL (e.g. `https://old.example.com`) and submit.
3. The new server calls `GET {old_url}/server-migration/export` with header `X-Migration-Secret: <MIGRATION_SECRET>`, receives a full JSON dump of `users`, `main_categories`, `sub_categories`, and `expenses`, and imports it inside a single DB transaction (`App\Services\MigrationImportService`).
4. A notification on success shows how many rows of each type were processed.

### Behavior notes

- **Idempotent for categories/expenses**: imported rows are tagged with `import_source`/`import_source_id` (the old server's host + original row ID), so re-running the same migration updates the same rows instead of duplicating them.
- **Not fully idempotent for users**: users are matched by `email` only. Re-running the migration will overwrite the `name` and `password` (hash, unmodified from the old server) of any local user sharing that email — including a user created independently on the new server after the first migration.
- Nothing is ever deleted — the import is purely additive/overwriting via `updateOrCreate`.
- The whole request is synchronous (no queueing/chunking), with a 120s HTTP timeout on the pull side and `throttle:10,1` on the export side — very large datasets may need to be migrated in a way that avoids request timeouts.

### Security — read before using in production

- **Both `/server-migration` and `/server-migration/export` are unauthenticated routes.** Access should be restricted at the network level (firewall/IP allowlist/VPN) in production — don't rely on the shared secret alone.
- `MIGRATION_SECRET` must be a strong random value, identical on both servers, and treated as a credential.
- The export dump includes **raw password hashes** for every user, with no per-user scoping. Only run this over HTTPS.
- The pull form makes an outbound request to whatever URL is entered, with the secret attached — only point it at old servers you trust.
- Consider disabling or removing this feature once a migration is complete.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
