# IPTV PHP + SQLite Webapp (simple)

Quick start:
1. Put files in your PHP web root.
2. Ensure PHP can write to `data/` (`chmod 755` or 775 as needed).
3. Run `setup.php` once in the browser to create the DB and seed an admin:
   - Admin username/password: `admin` / `admin123`
4. Visit:
   - `/` — homepage (browse iptv-org or uploaded playlists)
   - `/register.php` — create user
   - `/login.php` — login
   - `/user_dashboard.php` — upload playlists (m3u/.ita/text)
   - `/admin_dashboard.php` — admin area (manage ads/users/uploads)

Notes:
- The app uses the iptv-org API (https://iptv-org.github.io/api/) via a server-side proxy (`api_fetch.php`). The server must have outbound internet access.
- Uploaded playlists are stored in the database; the user dashboard extracts stream URLs and lets the user play them (basic HTML5 player).
- This is a minimal scaffold — for production hardening add HTTPS, CSRF tokens, input validation, rate-limits, and stronger access controls.
