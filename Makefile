up: docker-up
init: docker-down-clear docker-pull docker-build docker-up store-init
test: store-test

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

store-migrations:
	until docker-compose exec -T store-postgres pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done ; docker-compose run --rm store-php-cli php bin/console doctrine:migrations:migrate --no-interaction

logs:
	docker-compose logs --tail=100 -f $(c)

store-init: store-composer-install store-migrations

store-composer-install:
	docker-compose run --rm store-php-cli composer install

store-test:
	docker-compose run --rm store-php-cli php bin/phpunit
