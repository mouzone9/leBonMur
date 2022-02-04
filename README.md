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

Build the style :
```
cd app
yarn run build
```

##Development
Run docker containers and launch webpack dev server: 
```
docker-compose up -d
```
Go in container bash :
```
docker exec -it php8-sf6 bash
```
And start symfony server :
```
symfony server:start -d
```
Run the style  watcher :
```
cd app
yarn run watch
```

The server run finally at localhost:9000

For testing purpose, a first admin is automatically created, here is his credentials :
firstName = admin
password = password
