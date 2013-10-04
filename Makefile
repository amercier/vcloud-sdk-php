# The purpose of this Makefile is helping this project's contributors to setup
# a working environment. To do so, simply execute:
#
#     make
#
# This will, hopefully, download all the developer dependencies and get you
# started immediately. If this is not the case, please report an issue.
#
# @todo Check if XDebug is installed
# @todo Check if mcrypt is installed

.PHONY: update clean docs

build:
	cd ./docs && make

all: composer.lock
	# Dependencies installed/upgraded successfully

composer.lock: composer.phar
	[ -e composer.lock ] && rm composer.lock
	php composer.phar install

composer.phar:
	curl -sS https://getcomposer.org/installer | php

update: composer.phar
	php composer.phar update

test: composer.lock
	make lint && \
	vendor/bin/phpunit --coverage-html build/logs/coverage --coverage-text=build/logs/coverage.txt && \
	echo && \
	echo ======== Code coverage ======== && \
	cat build/logs/coverage.txt | grep -A3 Summary | tail -n 3 && \
	echo ===============================

integration: composer.lock
	make lint && \
	vendor/bin/phpunit --testsuite integration

unit: composer.lock
	make lint && \
	vendor/bin/phpunit --testsuite unit

unit-full: composer.lock
	make lint && \
	vendor/bin/phpunit --testsuite unit --coverage-html build/logs/coverage --coverage-text=build/logs/coverage.txt && \
	echo && \
	echo ======== Code coverage ======== && \
	cat build/logs/coverage.txt | grep -A3 Summary | tail -n 3 && \
	echo ===============================

lint: composer.lock
	vendor/bin/phpcs --standard=PSR1 src/ tests/
	vendor/bin/phpcs --standard=PSR2 src/ tests/

clean:
	rm -Rf vendor composer.phar composer.lock

docs:
	[ -d docs/api ] && rm -Rf docs/api || echo Nothing to do
	phpdoc --directory src/ --target docs/api --title "vCloud Director PHP SDK" --template responsive-twig
