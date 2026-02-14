.PHONY: help up down build logs shell db-shell migrate npm-install npm-build cache-clear

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

up: ## Start all containers
	docker compose up -d

down: ## Stop all containers
	docker compose down

build: ## Build and start all containers
	docker compose up -d --build

logs: ## Show container logs
	docker compose logs -f

shell: ## Open a shell in the PHP container
	docker compose exec php bash

db-shell: ## Open MySQL shell
	docker compose exec mysql mysql -ushorturl -pshorturl shorturl

install: ## Install all dependencies
	docker compose exec php composer install
	docker compose exec php npm install

migrate: ## Run database migrations
	docker compose exec php bin/console doctrine:migrations:migrate --no-interaction

npm-build: ## Build frontend assets
	docker compose exec php npm run build

cache-clear: ## Clear Symfony cache
	docker compose exec php bin/console cache:clear

schema-validate: ## Validate Doctrine schema
	docker compose exec php bin/console doctrine:schema:validate

setup: build install migrate npm-build ## Full project setup
	@echo "Setup complete! Visit http://localhost:8080/app/dashboard"
