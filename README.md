# Le bon mur

##Installation
Launch docker : 
```
docker-compose build
docker-compose up -d
```

Go in container bash :
```
docker exec -it php8-sf6 bash
```
And run migrations :
```
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

Install all 
````
cd app
composer install
yarn
````



##Development
Run docker containers and launch webpack dev server: 
```
docker-compose up -d
yarn run watch
```
Go in container bash :
```
docker exec -it php8-sf6 bash
```
And start symfony server :
```
symfony server:start -d
```

The server run finally at localhost:9000
