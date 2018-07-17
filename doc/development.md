# Development

Build image:

```bash
$ docker build -t damax-common .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common composer cs
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-common composer test
$ docker run --rm -v $(pwd):/app -w /app damax-common composer test-cc
```
