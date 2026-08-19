# Deploying GatePass on Dokploy

The image is a self-contained **FrankenPHP** server (PHP 8.3) that talks to your
**SQL Server** database. TLS is handled by Dokploy's built-in Traefik proxy, so the
container only serves plain HTTP on port **80**.

Files involved:
- `Dockerfile` — multi-stage build (Vite assets → Composer deps → FrankenPHP + `pdo_sqlsrv`).
- `docker/Caddyfile` — FrankenPHP config (serves `public/`, HTTP :80).
- `docker/entrypoint.sh` — prepares storage, caches config/routes/views, optional migrations.
- `docker-compose.yml` — web + scheduler + (optional) queue worker.
- `.env.docker.example` — the variables to set in Dokploy.

## Prerequisites
- The Dokploy server can reach your SQL Server host on port 1433.
- A domain (or subdomain) pointed at the Dokploy server.
- Your current production **APP_KEY** (reuse it so existing data/sessions stay valid).

## Recommended: Compose deployment (includes scheduler)
1. In Dokploy: **Create → Compose**, connect this Git repository (branch `main`).
2. Compose file path: `docker-compose.yml`.
3. Open **Environment** and paste the variables from `.env.docker.example`, filling in
   real values (`APP_KEY`, `APP_URL`, `DB_*`, mail…). Keep `DB_TRUST_SERVER_CERTIFICATE=true`
   unless your SQL Server has a trusted TLS certificate.
4. **Domains**: add your domain and map it to the **`app`** service, container port **80**.
   Enable HTTPS (Let's Encrypt) — Traefik terminates TLS.
5. **Volumes**: the `storage` volume is declared in the compose file and persists uploads,
   the public-storage symlink target and logs. Nothing else to configure.
6. First deploy: set `RUN_MIGRATIONS=true`, **Deploy**, then set it back to `false`
   and redeploy (so migrations don't re-run on every restart).

The `scheduler` service runs `php artisan schedule:work` (vehicle/material expiry, etc.).
Remove the `worker` service if you keep `QUEUE_CONNECTION=sync`.

## Alternative: Application (Dockerfile) deployment
1. **Create → Application**, connect the repo, **Build type = Dockerfile**.
2. Set the same environment variables.
3. **Domains** → port **80**; **Health check path** → `/up`.
4. Add a **persistent volume** mounted at `/app/storage`.
5. Because a single Application runs only the web process, create two more Applications from
   the same repo for background work, overriding the **start command**:
   - Scheduler: `php artisan schedule:work`
   - Worker (only if not `sync`): `php artisan queue:work --tries=3 --max-time=3600`

## Health check
The app exposes `GET /up` (returns 200 when the framework boots). Used by the compose
healthcheck and can be used as Dokploy's health path.

## Notes
- **Assets** are rebuilt inside the image (`npm run build`), so you don't rely on committed
  `public/build` output.
- **PDF generation** (spatie/browsershot) is **not** bundled with Chromium to keep the image
  small (the PDF/report downloads are currently disabled). If you re-enable them, add Node +
  Chromium to the runtime stage, or run rendering in a separate service.
- **APP_KEY** must be stable. If it is empty, the entrypoint generates a throwaway key and
  logs a warning — set a real one in Dokploy.
- To point at a different database, only the `DB_*` variables change; the image already
  contains the Microsoft ODBC driver and `pdo_sqlsrv`/`sqlsrv` extensions.
