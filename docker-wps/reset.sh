#!/bin/bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"
COMPOSE_CMD=(docker compose --env-file "$ROOT_DIR/.env" -f "$ROOT_DIR/docker-compose.yml" --profile tools)

echo "Stopping docker-wps and removing its containers, network, and database volume..."
"${COMPOSE_CMD[@]}" down -v --remove-orphans

echo "Reset complete."
echo "Removed only the docker-wps stack resources."
echo "Other Docker projects, images, and volumes were not touched."
