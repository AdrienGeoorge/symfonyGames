# symfonyGames

## Cloning the repository

Clone this project into your working directory. We recommend always running the master branch as it was frequent contributions.

```bash
$ git clone git@github.com:AdrienGeoorge/symfonyGames.git

Cloning into 'social-network'...
remote: Counting objects: 4794, done.
remote: Total 4794 (delta 0), reused 0 (delta 0)
Receiving objects: 100% (4794/4794), 1.59 MiB | 10.37 MiB/s, done.
Resolving deltas: 100% (2314/2314), done.
Checking connectivity... done.

```
## Docker
This project is very easy ton install in a Docker container
By default, the apache docker will expose port in 8010, so change this with in docker-compose.yml and here are the steps to follow, When ready
```bash
$ mv .env.test .env
  docker-compose up --build 
  docker-compose exec web php bin/console doctrine:database:creeate
  docker-compose exec web php bin/console doctrine:migration:migrate
  docker-compose exec web php bin/console doctrine:fixture:load 
```
## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## AUTHORS
* [Geoorge Adrien](https://github.com/AdrienGeoorge)
* [Buyuk Cem](https://github.com/BuyukCem)

## License
[MIT](https://choosealicense.com/licenses/mit/)
