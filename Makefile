.PHONY: build up down restart logs ps shell composer-install composer-update artisan-migrate artisan-seed artisan-cache-clear

# Build the Docker image
build:
	docker-compose build

# Start the containers
up:
	docker-compose up -d

# Stop the containers
down:
	docker-compose down

# Restart the containers
restart:
	docker-compose restart

# View logs
logs:
	docker-compose logs -f

# List running containers
ps:
	docker-compose ps

# Open shell in the PHP container
shell:
	docker-compose exec app bash

# Install Composer dependencies
composer-install:
	docker-compose exec app composer install

# Update Composer dependencies
composer-update:
	docker-compose exec app composer update

# Run database migrations
artisan-migrate:
	docker-compose exec app php artisan migrate

# Run database seeders
artisan-seed:
	docker-compose exec app php artisan db:seed

# Clear Laravel cache
artisan-cache-clear:
	docker-compose exec app php artisan cache:clear

# Help command
help:
	@echo "Available commands:"
	@echo "  make build              - Build the Docker image"
	@echo "  make up                 - Start the containers"
	@echo "  make down               - Stop the containers"
	@echo "  make restart            - Restart the containers"
	@echo "  make logs               - View container logs"
	@echo "  make ps                 - List running containers"
	@echo "  make shell              - Open shell in the PHP container"
	@echo "  make composer-install   - Install Composer dependencies"
	@echo "  make composer-update    - Update Composer dependencies"
	@echo "  make artisan-migrate    - Run database migrations"
	@echo "  make artisan-seed       - Run database seeders"
	@echo "  make artisan-cache-clear - Clear Laravel cache" 