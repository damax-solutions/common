## Development

Build image:

```bash
$ docker build -t damax-common-php .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-common-php composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-common-php ./vendor/bin/php-cs-fixer fix
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-common-php ./vendor/bin/simple-phpunit
$ docker run --rm -v $(pwd):/app -w="/app" damax-common-php ./bin/phpunit-coverage
```
