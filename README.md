## Development

Build image:

```bash
$ docker build -t damax-common .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-common composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-common ./vendor/bin/php-cs-fixer fix
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-common ./vendor/bin/simple-phpunit
$ docker run --rm -v $(pwd):/app -w="/app" damax-common ./bin/phpunit-coverage
```
