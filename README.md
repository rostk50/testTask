# PHP 8.4 + Laravel 12 API + Vue 3 (Vite) Starter

Single repo with Docker that runs backend and frontend in separate folders.

## Stack
- PHP 8.4, Laravel 12
- Node 22, Vue 3, Vite
- Postgres 16
- Redis 7
- Nginx

## Prereqs
- Docker 24+
- Docker Compose v2
- Make

## First run
```bash
make init
```

Backend URL: http://localhost:8080  
Frontend URL: http://localhost:5173

## Useful targets
```bash
make init           # up + backend-new + migrate + seed + frontend-install
make up             # start all services
make down           # stop and remove
make logs           # tail logs
make bash           # shell in backend-php
make backend-new    # create Laravel app in ./backend if missing
make backend-migrate
make seed
make test
make qa             # pint + phpstan + feature tests
make cache-clear
```

## API
GET /api/houses
Query params: name, bedrooms, bathrooms, storeys, garages, price_from, price_to
Response: {"data":[{"id":1,"name":"The Victoria","price":374662,"bedrooms":4,"bathrooms":2,"storeys":2,"garages":2}]}

## Tests
```
make test
```

## Postman
Import docs/Houses.postman_collection.json

GET /api/houses
Params:
- name
- bedrooms
- bathrooms
- storeys
- garages
- price_from
- price_to
- sort: id|name|price|bedrooms|bathrooms|storeys|garages
- dir: asc|desc
- page: int>=1
- per_page: 1..100

Response:
{
"data": [{ "id":1,"name":"The Victoria","price":374662,"bedrooms":4,"bathrooms":2,"storeys":2,"garages":2 }],
"meta": { "total":9,"per_page":10,"current_page":1,"last_page":1,"sort":"name","dir":"asc" }
}
