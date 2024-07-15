# Desenvolvimento de API Rest com PHP/MySql/Swagger/PHPUnity.
Pequena aplicação REST API com laravel 10, MySql para o banco de dados, PHPUnity para testes unitários e Swagger para documentação;


### Para iniciar o laravel com o docker:


1. na raiz do projeto, ``` $ cd test-api ```
2. ``` $ docker-compose up -d ```
3. ``` $ docker-compose exec agenda_api bash ```
4. ``` $ composer update ```
5. ``` $ php artisan key:generate ```
6. ``` $ php artisan jwt:secret ```
7. ``` $ php artisan optimize:clear ```


#### Para popular o banco de dados:

```
$ docker-compose exec agenda_api bash 
```

```
php artisan migrate:fresh --seed
```

### Teste o acesso da api em http://localhost/


#### Para realizar os testes:
1. ``` $ php artisan test ```
2. Ou diretamente com PHPUnity: ``` $ vendor/bin/phpunit ```


#### Para gerar e visualizar o Swagger:
1. ``` $ php artisan l5-swagger:generate ```
2. Acesse a documentação no navegador: ``` http://localhost/api/documentation ```
2. Faça o login e salveo token no botão: ``` Authorize ```


## Esta disponível o arquivo para api no postman na raiz do projeto!