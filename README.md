Instalação

1. Baixe o Laravel HERD
   - o Herd instala: php, laravel, composer e node
2. Instale o PostgreSQL e configure sua senha de usuario
3. no .env configure o banco como postgreSQL e coloque sua senha (o DB_username normalmente se chama postgres)


Setup do ambiente

1. No PgADMIN crie um banco de dados chamado sistema-de-estoque
2. Abra um terminal no local do projeto e faça as migrations: 
   php artisan migrate:fresh --seed (esse comando cria as tabelas no banco de dados)



Executando o projeto

1. Abra dois terminais na pasta do projeto
2. Em um deles digite npm run dev para compilar o CSS do Tailwind e deixe rodando
3. No outro terminal terminal digite php artisan serve
4. Acesse o navegador em localhost:8000

Caso esteja com problemas ao usar o php artisan serve
1. Clique com o botão direito no icone do herd e clique "open configuration files"
2. Vá para \bin\php84, abra o php.ini em um bloco de texto
3. Procure por "variables_order" e coloque seu valor como "GPCS" (ficará assim: variables_order = "GPCS")

