## Desenvolvido em:
Laravel versão 8

## Requisitos:
1. [PHP](https://www.php.net/) (7.4)
2. [MySQL](https://www.mysql.com) (5.7)
3. [Composer](https://getcomposer.org/) (2.1)

### Instruções para rodar o projeto (dentro da pasta do projeto):

1. Criar banco de dados
2. Rodar migrations
3. Rodar seeds (dados de teste)
3. Acesse a pasta do projeto
4. Execute os comandos abaixo, na raiz do projeto, em um terminal compativel com **Git Bash**, **PowerShell** ou **bash.exe**:

```sh
cp .env.localhost .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Ativar os Jobs de processando de transferencias

```bash
 php artisan queue:listen --queue=transferencias
```

