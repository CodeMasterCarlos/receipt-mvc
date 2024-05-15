# Receipt MVC

## Descrição
Receipt MVC é um projeto desenvolvido com o objetivo de praticar o padrão de arquitetura MVC (Model-View-Controller). Ele oferece um sistema simples de CRUD (Create, Read, Update, Delete) para gerenciar comprovantes, com funcionalidades de registro de usuário, autenticação, e busca utilizando a funcionalidade de fulltext do MySQL. Além disso, o projeto utiliza JWT (JSON Web Tokens) para criar tokens de autenticação, garantindo segurança nas operações de usuário.

## Instalação
Para executar este projeto localmente, siga estas etapas:

1. Clone este repositório para o seu computador:
```
git clone https://github.com/CodeMasterCarlos/receipt-mvc.git
```
2. Navegue até o diretório do projeto:
```
cd receipt-mvc
```
3. Instale as dependências:
```
composer install
```
3. Copie o arquivo .env.example para um novo arquivo .env
```
cp .env.example .env
```
4. Altere as informações do arquivo .env
```
vim .env
```
[Como configurar o arquivo .env](#como-configurar-o-arquivo-env)

5. Crie as tabelas no banco.
```
php migrations/tables.php
```

6. Inicio o servidor.
```
php -S localhost:8080 -t public/
```

## Como configurar o arquivo .env
| Constante  | Tipo de valor | Explicação |
| ------------- | ------------- | ------------- | 
| APP | {string} local/production | Referece ao ambiente da aplicação, para aplicação local os erros são exibidos com detalhes, enquanto para o valor production os erros são apresentado cpcom uma mensagem genérica de erro no servidor. | 
| DB_*  | {string} | Informações referente a conexão do seu banco de dados mysql. |
| JWT_KEY_*  | {string} | Caminho absoluto da chave pública e privada com algoritmo RS256. [Como criar chave RS256 com OpenSSL](#como-configurar-o-arquivo-env) |

## Como criar chave RS256 com OpenSSL

1. Gerar uma nova chave privada RSA:
```
openssl genrsa -out private_key.pem 2048
```
2. Extraia a chave pública da chave privada:
```
openssl rsa -pubout -in private_key.pem -out public_key.pem
```


## Tecnologias Utilizadas
- PHP
- MySQL
- JWT (JSON Web Tokens)

## Funcionalidades
- **Autenticação de Usuário:** Os usuários podem se cadastrar e fazer login para acessar recursos protegidos.
- **Gerenciamento de Comprovantes:** Os usuários podem criar, editar e excluir seus próprios comprovantes.
- **Integração com MySQL:** Os dados relacionado ao comprovante são armazenados em um banco de dados MySQL.
