.PHONY: up test

up:
	@docker compose up -d
	@docker compose exec php composer install --no-interaction

test: up
	@docker compose exec php vendor/bin/phpunit