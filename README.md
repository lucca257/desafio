## Desenvolvido em:
Laravel versão 8

## Requisitos:
1. [PHP](https://www.php.net/) (7.4)
2. [MySQL](https://www.mysql.com) (5.7)
3. [Composer](https://getcomposer.org/) (2.1)

### Instruções para instalar o projeto (dentro da pasta do projeto):

1. Entre no diretório
2. Gere o arquivo .env baseado no .env.example
3. "Levante" o projeto com o container
4. Gere uma chave válida de ambiente no env
5. Instale as dependências utilizando o composer
6. Gerando as tabelas utilizando as migrations

```sh
cd desafio
cp .env.example .env
docker-compose up -d --build
docker-compose exec php bash -c "php artisan key:generate; composer install; php artisan migrate --seed"
```

### Caso já tenha feito a instalação do projeto

obs: necessário o container estar levantador

```bash
docker-compose up -d --build
```

Ativar os Jobs de processando de transferencias

```bash
docker-compose exec php bash -c "php artisan queue:listen --queue=transferencias"
```

Realizar testes

```bash
docker-compose exec php bash -c "php artisan test"
```
### Diagramas

![image](https://user-images.githubusercontent.com/31326015/129606983-666d04d3-6486-4f24-a448-2e5628b39319.png)
![image](https://user-images.githubusercontent.com/31326015/129606632-d1a4cf52-9e50-4632-906d-3a5a3194007f.png)
### Rotas da aplicação
### Criar usuário
`POST api/v1/usuario`

    criar usuário
    
### Parâmetros

    {
        "nome" : "pedro lucca leonardo de almeida",
        "cpf_cnpj" : "13864107725",
        "email" : "pedrolucca257@gmail.com",
        "password" : "any_password",
        "tipo_id" : 1
    }
    
    tipos possíveis:
    1. usuário comum
    2. lojista
    
### Response

    {
        "status": "successo",
        "message": "transferencia processada com sucesso"
    }


### Realizar Transferenciar

`POST api/v1/transferir`

    Realizar transferências entre usuários
    
### Parâmetros

    {
        "pagador" : 1,
	    "beneficiario" : 2,
        "valor": 1
    }

### Response

    {
        "status": "successo",
        "message": "transferencia processada com sucesso"
    }
