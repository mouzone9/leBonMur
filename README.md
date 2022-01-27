# Le bon mur

##Installation
Install all : 
```
composer install
yarn
docker-compose build
```

Go in container bash :
```
docker exec -it php8-sf6 bash
```
And run migrations :
```
symfony console make:migrations
symfony console doctrine:fixtures:load
```

##Development
Run docker containers and launch webpack dev server: 
```
docker-compose up -d
yarn run dev-server
```
Go in container bash :
```
docker exec -it php8-sf6 bash
```
And start symfony server :
```
symfony server:start -d
```
