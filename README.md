# clone-twitter

<center>

![Imagem de capa](https://github.com/EvelynGitHub/assets/blob/main/clone-twitter/clone-twitter.png)


</center>
<center>
<!--Site útil para pegar as imagens que aparecem https://shields.io -->

[![NPM](https://img.shields.io/npm/l/react)](https://github.com/EvelynGitHub/clone-twitter/LICENSE)
<!-- ![GitHub](https://img.shields.io/github/license/EvelynGitHub/assets-readme) -->


</center>

# Sobre o Projeto:

[Demo do Projeto](https://evelyn-clone-twitter.netlify.app)

Este projeto teve inicio como um teste para uma vaga de emprego, mas acabou se tornando um projeto experimental para aplicar novos conhecimentos e meios alternativos de se organizar.

O objetivo do projeto é ser um clone simplificado da famosa rede socil Twitter. 

Como **funções do projeto** temos:
- Criação de Tweet;
- Criação de Comentário em Tweet;
- Possibilidade de Seguir um outro Usuário;
- Criar, Editar e Deletar sua conta de Usuário;
- Além de um Login/Logout simples;

E como aplicação de **novos conhecimentos** temos:
- Login com autententicação JWT;
- Aplicação de API REST Full;
- Axios para as requisições do frontend;
- Frontend utilizando o novo framework svelte;
- Paginação de Tweets e Comentários;

O projeto **não aborda**:
- Sistema de recuperação de senha;
- Sistema de notificação de novos Tweets/Comentários;
- Sistema de #;
- Sistema de curtidas;

## Layout mobile

![Mobile 1](https://github.com/EvelynGitHub/assets/blob/main/clone-twitter/clone-mobile-1.png) ![Mobile 2](https://github.com/EvelynGitHub/assets/blob/main/clone-twitter/clone-mobile-2.png)

## Layout Web

![Web 1](https://github.com/EvelynGitHub/assets/blob/main/clone-twitter/clone-login.png)

![Web 2](https://github.com/EvelynGitHub/assets/blob/main/clone-twitter/clone-home.png)

## Modelo conceitual

### MER

![Modelo de Entidade e Relacionamento](https://github.com/EvelynGitHub/assets/blob/main/clone-twitter/MER%20-%20clone-twitter-2.png)

# Tecnologias utilizadas:

## Back end

- PHP
- Composer

## Front end

- HTML / CSS / JS 
- Nodejs
- Svelte

## Implantação em produção

- Back end: Heroku
- Front end web: Netlify
- Banco de dados: PostgreSQL

# Por onde começar:

## Back end

Pré-requisitos: PHP 7.4, Composer e Apache(Lampp, Xampp, Wamp).

```bash
# clonar repositório dentro do servidor apache
git clone https://github.com/EvelynGitHub/clone-twitter.git

# entrar na pasta do projeto back end
cd clone-twitter/backend

# executar o projeto
composer install
```

Agora, ainda dentro de <code> backend </code>, crie um arquivo chamado <code> env.php </code>. Este é o arquivo que vai conter as váriaveis de ambiente, nele você deve colocar as informações para ele acessar o banco que você criou segundo o **digrama MER** mostrado acima. Seu conteúdo deve ser similar a esse:

```php
<?php
    // Essa URL pode mudar dependendo do que estiver usando
    putenv("URL=http://localhost/clone-twitter");

    putenv("DB_DRIVE=pgsql");

    putenv("DB_HOST=localhost");

    putenv("DB_PORT=5432");

    putenv("DB_NAME=nome_do_banco_criado_por_você");

    putenv("DB_USER_NAME=root");

    putenv("DB_USER_PASSWD=sua_senha_de_usuario");

```

Agora que já mudou o que tinha que mudar, você pode testar o backend com algum software com Insomnia ou Postman. 

**OBS.:**Você pode usar um banco MYSQL para testar também, mas pode ser que alguma coisa da paginação não funcione 100%.

## Front end web

Pré-requisitos: npm / yarn

O frontend é independente do backend, isso quer dizer que você pode executar apenas ele. Os dados que você vai acessar serão os da API que já está implementada. Para executar o frontend:

```bash
# clonar repositório
git clone https://github.com/EvelynGitHub/clone-twitter.git

# entrar na pasta do projeto front end web
cd clone-twitter/frontend

# instalar dependências
npm i

# executar o projeto
npm run dev
```

Caso queira testar junto do backend local, você deve entrar dentro de <code> frontend/src/api/Service.js </code> e mudar a linha <code>baseURL</code> para algo como:
```php
const axiosAPI = axios.create({
    baseURL: "http://localhost/clone-twitter" 
});
```

Lembre-se que o valor da <code>baseURL</code> deve coincidir com o valor da variável de ambiente <code>URL</code> colocado no <code>env.php</code>.

# Como começar a usar:

Como um Usuário não logado você pode acessar o HOME, os perfils dos cadastrados e é claro o login.

Se registre para poder ver tudo o que é possível fazer. O reste você descobre usando.

# Autor(es)

**Evelyn Francisco Brandão**

https://www.linkedin.com/in/evelyn-brandão
