#!/bin/bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_ROOT="$(cd "$ROOT_DIR/.." && pwd)"
WEBROOT_DIR="$PROJECT_ROOT/webroot"
COMPOSE_CMD=(docker compose --env-file "$ROOT_DIR/.env" -f "$ROOT_DIR/docker-compose.yml" --profile tools)

echo "Stopping docker-wps and removing its containers, network, and database volume..."
"${COMPOSE_CMD[@]}" down -v --remove-orphans

echo "Cleaning generated WordPress files from webroot..."

# Remove generated root files and folders, but keep wp-content and tracked placeholders.
find "$WEBROOT_DIR" -mindepth 1 -maxdepth 1 \
  ! -name '.gitkeep' \
  ! -name 'wp-content' \
  -exec rm -rf {} +

# Remove generated content under wp-content, but preserve starter structure and theme.
find "$WEBROOT_DIR/wp-content" -mindepth 1 -maxdepth 1 \
  ! -name 'mu-plugins' \
  ! -name 'plugins' \
  ! -name 'themes' \
  ! -name 'languages' \
  ! -name 'upgrade' \
  ! -name 'uploads' \
  -exec rm -rf {} +

find "$WEBROOT_DIR/wp-content/themes" -mindepth 1 -maxdepth 1 \
  ! -name '.gitkeep' \
  ! -name 'your-theme' \
  -exec rm -rf {} +

find "$WEBROOT_DIR/wp-content/plugins" -mindepth 1 -maxdepth 1 -exec rm -rf {} +
find "$WEBROOT_DIR/wp-content/mu-plugins" -mindepth 1 -maxdepth 1 -exec rm -rf {} +
find "$WEBROOT_DIR/wp-content/languages" -mindepth 1 -maxdepth 1 -exec rm -rf {} +
find "$WEBROOT_DIR/wp-content/upgrade" -mindepth 1 -maxdepth 1 -exec rm -rf {} +
find "$WEBROOT_DIR/wp-content/uploads" -mindepth 1 -maxdepth 1 -exec rm -rf {} +

echo "Hard reset complete."
echo "Docker resources removed and webroot cleaned."
echo "Preserved: webroot/wp-content starter structure and theme your-theme."
