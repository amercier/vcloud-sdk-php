

SRC = vendor/vmware/vcloud-sdk/library/
OUT = library/

all: composer.lock config.mk

composer.lock: composer.phar
	php composer.phar install

composer.phar:
	curl -sS https://getcomposer.org/installer | php

test:
	cd tests && make

install: composer.phar
	php composer.phar install

update: composer.phar
	php composer.phar update
