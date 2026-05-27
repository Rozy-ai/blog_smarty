COMPOSE=docker compose
PHP=php
HOST=localhost
PORT=8080

.PHONY: help install up down restart seed logs shell ps local-install local-seed local-serve lint

help:
	@echo "Available commands:"
	@echo "  make install       Install PHP dependencies with Composer"
	@echo "  make up            Build and start Docker containers"
	@echo "  make down          Stop Docker containers"
	@echo "  make restart       Restart Docker containers"
	@echo "  make seed          Seed database inside Docker app container"
	@echo "  make logs          Show Docker logs"
	@echo "  make shell         Open shell inside Docker app container"
	@echo "  make ps            Show Docker containers status"
	@echo "  make local-install Install dependencies for local run"
	@echo "  make local-seed    Seed database for local run"
	@echo "  make local-serve   Start built-in PHP server"
	@echo "  make lint          Check PHP syntax in project files"

install:
	composer install

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

restart: down up

seed:
	$(COMPOSE) exec app $(PHP) database/seed.php

logs:
	$(COMPOSE) logs -f

shell:
	$(COMPOSE) exec app bash

ps:
	$(COMPOSE) ps

local-install:
	composer install

local-seed:
	$(PHP) database/seed.php

local-serve:
	$(PHP) -S $(HOST):$(PORT) -t public

lint:
	find src public database -name "*.php" -print0 | xargs -0 -n1 $(PHP) -l
