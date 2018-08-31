# Sample application

Check [this document](../doc/development.md) how to build _Docker_ image.

## Installation

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common composer install
```

Create schema:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common ./bin/console doctrine:schema:update --force
```

Create table for enqueue:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common ./bin/console enqueue:setup-broker
```

Import fixtures:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common ./bin/console doctrine:fixture:load
```

Consume messages:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common ./bin/console enqueue:transport:consume enqueue.simple_bus.events_processor --queue=domain_events --message-limit=25
```

## Usage

Run built-in _HTTP_ server:

```bash
$ docker run --rm -v $(pwd):/app -w /app -p 8080:8080 damax-common ./bin/console server:run *:8080
```

Browse _API_ docs:

```bash
$ open http://127.0.0.1:8080/api/doc
```

## Contribute

Fix coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common ./vendor/bin/php-cs-fixer fix
```
