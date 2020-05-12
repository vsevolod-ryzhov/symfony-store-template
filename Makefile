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

store-init: store-composer-install

store-composer-install:
	docker-compose run --rm store-php-cli composer install

store-test:
	docker-compose run --rm store-php-cli php bin/phpunit
