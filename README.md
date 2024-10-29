docker-compose -f docker-compose.dev.yml up --build -d

`docker exec -ti publisher_php bash`

test
`php bin/console doctrine:migrations:migrate --env=test`
`php bin/console --env=test doctrine:fixtures:load`