# Sample application

## Installation

Create schema:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common ./bin/console doctrine:schema:update --force
```

Import fixtures:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common ./bin/console doctrine:fixture:load
```

## Usage

Run built-in HTTP server:

```bash
$ docker run --rm -v $(pwd):/app -w /app -p 8080:8080 damax-common ./bin/console server:run *:8080
```

## Contribute

Fix coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common ./vendor/bin/php-cs-fixer fix
```
