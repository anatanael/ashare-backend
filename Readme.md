# O que é o Ashare?

O Ashare é um aplicativo que desenvolvi para aprimorar minhas habilidades em backend e frontend. Durante o desenvolvimento, aprendi conceitos práticos de aplicação ao implementar uma API que se comunica com o frontend, persistindo informações em um banco de dados, criando usuários e utilizando estratégias de autenticação com JWT para manter os usuários conectados.

O Ashare foi projetado para compartilhar conteúdo de forma simples e fácil. 🚀

Principais Funcionalidades 🛠️

- Envio de Texto Anonimamente: Permite que você envie textos de forma anônima, sem a necessidade de criar uma conta;
- Criar Conta: Registre-se para acessar funcionalidades adicionais e manter seus conteúdos organizados;
- Criar Categoria: Crie categorias personalizadas para organizar seus textos de maneira eficiente;
- Envio de Texto Associado a uma Categoria: Adicione textos a categorias específicas, facilitando a organização e o acesso posterior.

## Demo

<a href="https://ashare.epizy.com">Página demostração</a>

<p align="center">
  <img width="800" hspace="10" vspace="10" target="_blank"/ src="https://github.com/anatanael/ashare-web/assets/51931199/185019b4-00d3-44e9-835b-37151323096b">
</p>

## Frontend

<a href="https://github.com/anatanael/ashare-web">O código do frontend pode ser acessado aqui</a>

## Estrutura banco de dados

<p align="center">
  <img width="800" hspace="10" vspace="10" target="_blank"/ src="https://github.com/anatanael/ashare-web/assets/51931199/bfa69ffc-3481-4c91-97e2-1448924a5241"/>
</p>

## Tecnologias utilizadas no backend

- Laravel + Docker

## Configuração de Variáveis de Ambiente

O projeto utiliza variáveis de ambiente para configurar ambientes distintos

- JWT_SECRET -> Token secret para gerar validação de usuário na api;
- JWT_TTL -> Duração do token em milissegundos
- APP_TIMEZONE -> Para controle do horário do banco de dados. Ex: "America/Sao_Paulo"

## Instalação

```bash
docker-compose up -d
```

```bash
docker-compose exec app bash
```

## Banco de Dados

### Migrations

```bash
php artisan migrate
```

# Rotas da aplicação

#### Rotas que não tiverem todos os campos obrigatórios preenchidos retornaram como resposta

400 Bad Request

#### Se o servidor falhar vai retornar

500 Internal Server Error

## Public Endpoints

### Criar nota

```http
  POST /note
```

Request

```json
{
  "text": "Lorem Ipsum is simply dummy text of the printing"
}
```

| Parameter | Type     | Description  |
| :-------- | :------- | :----------- |
| `text`    | `string` | **Required** |

Response

```json
{
  "id": 1,
  "categoryId": null,
  "userId": null,
  "text": "teste",
  "createdAt": "2024-05-22 19:57:14",
  "updatedAt": null
}
```

Códigos de Status:

- 200 Ok

### Ver todas as notas

```http
  GET /note
```

Response

```json
[
  {
    "id": 27,
    "categoryId": null,
    "userId": null,
    "text": "Lorem Ipsum is simply dummy text of the printing",
    "createdAt": "2024-05-24 20:31:24",
    "updatedAt": null
  },
  {
    "id": 29,
    "categoryId": null,
    "userId": null,
    "text": "It has survived not only five centuries",
    "createdAt": "2024-05-24 20:32:06",
    "updatedAt": null
  }
]
```

Códigos de Status:

- 200 Ok

### Excluir nota

```http
  DELETE /note/{id_note}
```

Códigos de Status:

- 200 Ok
- 404 Not Found

### Criar conta

```http
  POST /user
```

Request

```json
{
  "name": "usuario",
  "username": "usuario",
  "password": "usuario",
  "email": "usuario@email.com"
}
```

| Parameter  | Type     | Description  |
| :--------- | :------- | :----------- |
| `name`     | `string` | **Required** |
| `email`    | `string` | **Required** |
| `username` | `string` | **Required** |
| `password` | `string` | **Required** |

Response

```json
{
  "id": 8,
  "name": "usuario",
  "username": "usuario",
  "email": "usuario@email.com",
  "urlImage": null,
  "createdAt": "2024-05-24 20:11:41"
}
```

Códigos de Status:

- 200 Ok
-
- 409 Conflict: Usuário ou e-mail já cadastrado(s)

### Login

```http
  POST /login
```

Request

```json
{
  "username": "usuario",
  "password": "usuario"
}
```

| Parameter  | Type     | Description  |
| :--------- | :------- | :----------- |
| `username` | `string` | **Required** |
| `password` | `string` | **Required** |

Response

```json
{
  "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6OCwidXNlcm5hbWUiOiJ1c3VhcmlvIiwiZW1haWwiOiJ1c3VhcmlvQGVtYWlsLmNvbSIsImlhdCI6MTcxNjU5MjUzMCwiZXhwIjoxNzE2NTk5NzMwfQ.erZwSDgM84_nAQCdu0NFUhhorvkW_yzpecLySZKFif4",
  "refreshToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6OCwidXNlcm5hbWUiOiJ1c3VhcmlvIiwiZW1haWwiOiJ1c3VhcmlvQGVtYWlsLmNvbSIsImlhdCI6MTcxNjU5MjUzMCwiZXhwIjoxNzE5MTg0NTMwfQ.XCgEffZtbGGKfNxQ3Y5mzerK1wmPUvhSnTJJHZJEMBo",
  "user": {
    "name": "usuario"
  }
}
```

Códigos de Status:

- 200 Ok
- 401 Unauthorized: Usuário e/ou senha inválido(s)

## Private Endpoints

#### Rotas privadas requerem token válido, caso contrário retornaram como resposta

401 Unauthorized

## Categoria usuário

### Criar categoria associada a um usuário

```http
  POST /user/category
```

Request

```json
{
  "title": "Principal"
}
```

| Parameter | Type     | Description  |
| :-------- | :------- | :----------- |
| `title`   | `string` | **Required** |

Response

```json
{
  "id": 57,
  "title": "Principal",
  "userId": 8,
  "createdAt": "2024-05-24 20:59:25"
}
```

Códigos de Status:

- 200 Ok

### Atualizar imagem da categoria

Request

```sh
curl -X POST http://api.com/user/category/${id_category}/image \
  -H "Content-Type: multipart/form-data" \
  -F "cover=@/caminho/do/arquivo"
```

| Parameter | Type   | Description  |
| :-------- | :----- | :----------- |
| `cover`   | `file` | **Required** |

Response

```json
{
  "id": "1",
  "urlImage": "https://i.imgur.com/IodehRb.png",
  "hashDeleteImage": "4mGe9sT6RS5Q51a"
}
```

Códigos de Status:

- 200 Ok

### Ver todas as categorias do usuário

```http
  GET /user/category
```

Response

```json
[
  {
    "id": 57,
    "title": "Principal",
    "urlImage": null,
    "hashDeleteImage": null,
    "createdAt": "2024-05-24 20:59:25",
    "updatedAt": null
  },
  {
    "id": 58,
    "title": "Secundaria",
    "urlImage": null,
    "hashDeleteImage": null,
    "createdAt": "2024-05-24 21:01:28",
    "updatedAt": null
  }
]
```

Códigos de Status:

- 200 Ok

### Excluir categoria do usuário

```http
  DELETE /user/category/{id_category}
```

Códigos de Status:

- 200 Ok
- 404 Not Found

## Notas

### Criar nota associada a uma categoria

```http
  POST /user/note/category/{id_category}
```

Request

```json
{
  "text": "Lorem Ipsum is simply dummy text of the printing and typesetting industry"
}
```

| Parameter | Type     | Description  |
| :-------- | :------- | :----------- |
| `text`    | `string` | **Required** |

Response

```json
{
  "id": 30,
  "categoryId": 57,
  "userId": 8,
  "text": "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
  "createdAt": "2024-05-24 21:26:06",
  "updatedAt": null
}
```

Códigos de Status:

- 200 Ok
- 403 Forbidden: Usuário não é dono da categoria

### Ver todas as notas do usuário associada a uma categoria

```http
  GET /user/note/category/{id_category}
```

Response

```json
[
  {
    "id": 30,
    "categoryId": 57,
    "userId": 8,
    "text": "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
    "createdAt": "2024-05-24 21:26:06",
    "updatedAt": null
  },
  {
    "id": 31,
    "categoryId": 57,
    "userId": 8,
    "text": "Lorem Ipsum is simply dummy text of the printing and typesetting industry",
    "createdAt": "2024-05-24 21:28:11",
    "updatedAt": null
  }
]
```

Códigos de Status:

- 200 Ok

### Excluir nota do usuário

```http
  DELETE /user/note/{id_note}
```

Códigos de Status:

- 200 Ok
- 404 Not Found
