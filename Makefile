SAIL := ./vendor/bin/sail

.DEFAULT_GOAL := help

.PHONY: help up down start stop restart build logs ps shell dev npm artisan composer migrate test

help:
	@echo "Laravel Sail — Docker (PHP 8.3 + MySQL 8.0)"
	@echo ""
	@echo "Containers:"
	@echo "  make up          Start containers (detached)"
	@echo "  make down        Stop and remove containers"
	@echo "  make start       Start stopped containers"
	@echo "  make stop        Stop containers"
	@echo "  make restart     Restart containers"
	@echo "  make build       Rebuild Sail images"
	@echo "  make logs        Follow container logs"
	@echo "  make ps          Show container status"
	@echo ""
	@echo "Development (Sail replaces composer run dev for containerized PHP):"
	@echo "  1. make up"
	@echo "  2. Open APP_URL (e.g. http://localhost when APP_PORT=80)"
	@echo "  3. make dev     — Vite dev server (HMR / CSS hot reload)"
	@echo "  Optional in other terminals:"
	@echo "  make artisan ARGS=\"queue:work\""
	@echo "  make artisan ARGS=\"pail\""
	@echo ""
	@echo "Passthrough:"
	@echo "  make migrate     — sail artisan migrate (use ARGS for flags, e.g. ARGS=\"--seed\")"
	@echo "  make artisan ARGS=\"migrate\""
	@echo "  make npm ARGS=\"install\""
	@echo "  make composer ARGS=\"install\""
	@echo "  make shell"
	@echo ""
	@echo "Host-only (no Docker): composer run dev"

up:
	$(SAIL) up -d

down:
	$(SAIL) down

start:
	$(SAIL) start

stop:
	$(SAIL) stop

restart:
	$(SAIL) restart

build:
	$(SAIL) build --no-cache

logs:
	$(SAIL) logs -f

ps:
	$(SAIL) ps

shell:
	$(SAIL) shell

dev:
	$(SAIL) npm run dev

npm:
	$(SAIL) npm $(ARGS)

artisan:
	$(SAIL) artisan $(ARGS)

composer:
	$(SAIL) composer $(ARGS)

migrate:
	$(SAIL) artisan migrate $(ARGS)

test:
	$(SAIL) artisan test
