COMPOSER_RUN = docker-compose run --rm composer
APP_RUN = docker-compose run --rm app

all: composer-install setup serve
.PHONY: all

setup:
		$(APP_RUN) bin/console doctrine:schema:update --force
		$(APP_RUN) bin/console enqueue:setup-broker
		$(APP_RUN) bin/console doctrine:fixtures:load -q
.PHONY: setup

serve:
		docker-compose run --rm -p 8090:8090 app bin/console server:run *:8090
.PHONY: serve

consume:
		$(APP_RUN) bin/console enqueue:transport:consume enqueue.simple_bus.events_processor \
			--queue=domain_events \
			--message-limit=25
.PHONY: consume

composer-install:
		$(COMPOSER_RUN) install
.PHONY: composer-install

composer-update:
		$(COMPOSER_RUN) update
.PHONY: composer-update
