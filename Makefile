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

store-init: store-composer-install store-assets-install store-migrations store-fixtures

store-composer-install:
	docker-compose run --rm store-php-cli composer install
	docker-compose run --rm store-node npm rebuild node-sass

store-assets-install:
	docker-compose run --rm store-node yarn install

store-assets-dev:
	docker-compose run --rm store-node npm run dev

store-fixtures:
	docker-compose run --rm store-php-cli php bin/console doctrine:fixtures:load --no-interaction

store-test:
	docker-compose run --rm store-php-cli php bin/phpunit
