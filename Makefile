COMPOSER_RUNTIME = -e SYMFONY_PHPUNIT_VERSION=7.4

DOCKER_RUN = docker run --rm -v $(PWD):/app -w /app $(COMPOSER_RUNTIME) prooph/composer:7.2

all: install test
.PHONY: all

install:
		$(DOCKER_RUN) install
.PHONY: install

update:
		$(DOCKER_RUN) update
.PHONY: update

cs:
		$(DOCKER_RUN) run-script cs
.PHONY: cs

test:
		$(DOCKER_RUN) run-script test
.PHONY: test

test-cc:
		$(DOCKER_RUN) run-script test-cc
.PHONY: test-cc
