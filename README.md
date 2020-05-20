# Client Scoring Application

### Installation

1. Start in terminal: `git clone https://github.com/N-Porsh/task-scoring-app.git`
2. `cd task-scoring-app/docker`
3. `docker-compose up -d`
4. Once everything is installed, you will need to wait about 1-3 minutes(depends on the machine) to start the servers work properly
5. `docker-compose run php-fpm bin/console doctrine:fixtures:load`
5. navigate to: `http://localhost`. Works on port 80 - so make sure the port is not busy.

NB! `502 Bad Gateway` ? - Just wait more time until everything is started (3 minutes in my case) 

### Compose

#### PHP (PHP-FPM)

Composer is included

```
docker-compose run php-fpm composer 
```

To run fixtures

```
docker-compose run php-fpm bin/console doctrine:fixtures:load
```


#### Console Command
Options:

1. `php bin/console calculate-credit-score`
2. `php bin/console calculate-credit-score 1` - {clientID}

