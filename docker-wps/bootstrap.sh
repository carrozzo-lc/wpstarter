#!/bin/bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"
COMPOSE_CMD=(docker compose --env-file "$ROOT_DIR/.env" -f "$ROOT_DIR/docker-compose.yml")

cd "$ROOT_DIR"

while IFS= read -r line || [ -n "$line" ]; do
  case "$line" in
    ''|\#*)
      continue
      ;;
  esac

  export "$line"
done < "$ROOT_DIR/.env"

echo "Starting Docker services..."
"${COMPOSE_CMD[@]}" up -d

echo "Waiting for WordPress core files and wp-config.php..."
until "${COMPOSE_CMD[@]}" exec -T wp sh -lc 'test -f /var/www/html/wp-load.php && test -f /var/www/html/wp-config.php'; do
  sleep 2
done

echo "Waiting for database connectivity from WP-CLI..."
until "${COMPOSE_CMD[@]}" --profile tools run --rm wpcli db check --skip-ssl >/dev/null 2>&1; do
  sleep 3
done

if "${COMPOSE_CMD[@]}" --profile tools run --rm wpcli core is-installed >/dev/null 2>&1; then
  echo "WordPress is already installed."
else
  echo "Installing WordPress with WP-CLI..."
  "${COMPOSE_CMD[@]}" --profile tools run --rm wpcli core install \
    --url="$WP_SITE_URL" \
    --title="$WP_SITE_TITLE" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email
fi

echo "Activating theme: $WP_ACTIVE_THEME"
"${COMPOSE_CMD[@]}" --profile tools run --rm wpcli theme activate "$WP_ACTIVE_THEME"

echo "Verifying WordPress installation..."
SITE_URL=$("${COMPOSE_CMD[@]}" --profile tools run --rm wpcli option get siteurl)
ACTIVE_THEME=$("${COMPOSE_CMD[@]}" --profile tools run --rm wpcli theme list --status=active --field=name)

echo "Bootstrap complete."
echo "Open the site: $WP_SITE_URL"
echo "Open the admin: $WP_SITE_URL/wp-admin"
echo "Admin user: $WP_ADMIN_USER"
echo "Admin password: $WP_ADMIN_PASSWORD"
echo "Verified siteurl: $SITE_URL"
echo "Verified active theme: $ACTIVE_THEME"
