# Client Scoring Application

### Installation

1. clone repository
2. go to the cloned projects root directory in terminal
3. `cd docker`
4. `docker-compose up -d`
5. Once everything is installed, you will need to wait about 1-3 minutes(depends on the machine) to start the servers work properly
6. navigate to: `http://localhost`. Works on port 80 - so make sure the port is not busy. 

## Compose

### PHP (PHP-FPM)

Composer is included

```
docker-compose run php-fpm composer 
```

To run fixtures

```
docker-compose run php-fpm bin/console doctrine:fixtures:load
```


### Console Command
Options:

1. `php bin/console calculate-credit-score`
2. `php bin/console calculate-credit-score 1` - {clientID}

