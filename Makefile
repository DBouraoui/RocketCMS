.PHONY: test

test:
	@echo "🧹 Resetting test database..."
	php bin/console --env=test doctrine:database:drop --force --if-exists
	php bin/console --env=test doctrine:database:create
	php bin/console --env=test doctrine:migrations:migrate --no-interaction
	php bin/console --env=test doctrine:fixtures:load --no-interaction
	@echo "✅ Running PHPUnit tests..."
	php bin/phpunit
