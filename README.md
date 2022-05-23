# PHP Request Validator

PHP project that implements Request Validation using Respect\Validation

## Description

Implementation example of Request Validation in Slim 3 Framework using only
Respect\Validation library, based on Laravel FormRequest.

### Creating validation

Create a FormRequest derivated class with the rules list (see Respect\Validation docs)
to know more about the available rules.

## Future improvementes

- [ ] Possibility to inject FormRequest subclass on controller method
- [ ] Possibility to prepare data for validation
- [ ] Possibility to get cleaned data after validation

## How to Use

With this template it is possible to:

- Start the environment
```console
docker-compose -f "docker-compose.yml" up -d --build
```
The application will be available at http://localhost:8080

  
- Install PHP dependencies with composer
```console
docker-compose run composer composer <composer command>
# alternativelly you can use the Makefile helper
make composer "<composer command>"
```

- Run PhpUnit tests
```console
docker-compose exec php php ./vendor/bin/phpunit
# to specify a test file
docker-compose exec php php ./vendor/bin/phpunit path/to/FileTest.php

# alternativelly you can use the Makefile command

make test
# or
make test /path/to/FileTest.php
```