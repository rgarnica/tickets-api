# Tickets API

API para exposição de tickets e análise da prioridade de cada um.

## Iniciando

Os próximos tópicos o guiarão para que você tenha uma cópia deste projeto em sua máquina local funcionando, pronto para desenvolver e executar testes.

### Pré-requisitos

* PHP 7.2+
* GIT
* Composer
* Recomendado (Opcional): Docker

### Instalação

Passo a passo para executar a aplicação

Clone o repositório em seu computador:

```
git clone https://github.com/rgarnica/tickets-api.git
```

Crie o arquivo .env a partir de uma cópia do exemplo:

```
cp .env.example .env
```

Se possuir o docker instalado em seu computador (Recomendo que tenha), acesse o diretório da aplicação e rode os containers:

```
docker-compose up -d
```
Desta forma a aplicação já está rodando e você pode acessá-la em http://localhost:8080.

Caso a porta já esteja em uso por outra aplicação em seu computador você pode ter problemas. Se necessário, mude o mapeamento das portas no arquivo `docker-compose.yml`.

Se enfrentar problemas de permissão de gravação no file system, por favor dê permissao na pasta `storage` em seu computador e reinicie os containers.

#### Classificação dos tickets

Para classificar os tickets, use o seguinte comando:

```
php artisan tickets:classify app/sentences.csv
```
Esse comando criará os índices PriorityLabel e PriorityPoints a cada ticket.

Obs: Se estiver usando o docker, para executar esse comando entre no container usando o seguinte comando no diretório da aplicação:

```
docker-compose exec php bash
```


### API End Points
Links disponíveis na API.
#### Públicos
Serviços que não precisam de autenticação do usuário
##### Tickets
GET /api/v1/tickets

Parâmetro         | Formato         | Descrição
----------------- | --------------- | -------------------------------------------------------
date_create_start | YYYY-MM-DD      | Para filtrar tickets pela data de criação. Data Inicial.
date_create_end   | YYYY-MM-DD      | Data final do intervalo de filtro. As duas devem ser informadas.
priority_label    | Normal\|Alta    | Filtrar por prioridade
page              | número          | Controle da paginação
order_by          | campo.asc\|desc | Ordenação dos itens. Exemplo: date_create.asc.<br/>priority_points.asc

## Rodando os testes

Para rodar os testes unitários, entre na pasta da aplicação e use o seguinte comando:
```
vendor/bin/phpunit
```

## Ferramentas Utilizadas

* [Lumen](https://lumen.laravel.com/docs/5.7) - Framework Utilizado
* [PHP-ML](https://php-ml.readthedocs.io/en/latest/) - Biblioteca Machine Learning para classificar tickets
