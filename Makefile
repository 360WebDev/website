.PHONY: install update test

composer.lock: composer.json
	composer update

vendor: composer.lock
	composer install

install: vendor

serve: install
	php artisan serve

test:
	./vendor/bin/phpunit --colors

ini_env:
	cp .env.example .env
	php artisan key:generate

cln_std:
	./vendo
