Запуск:

Postgres + pgadmin в docker-compose.yml.exmpl

composer install

php yii migrate

php yii serve --port 8081 

Генерация документации:

./vendor/bin/openapi -b ./OpenApi/swagger-bootstrap.php -o ./web/swagger-ui/swagger.json -e ./vendor ./

Swagger-ui:

http://localhost:8081/swagger-ui
