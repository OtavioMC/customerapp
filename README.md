## Instalação

1 - Dependências de versões: PHP > 8.2, MySQL >= 5.5, Composer >= 2.6

2 - No seu terminal local, rode "composer install" para baixar e configurar as dependências de desenvolvimento do projeto

3 - Crie o banco de dados no seu phpmyadmin com o comando 'create database bd_customerapp';

4 - Utilizando o .env.example como referência, crie na raiz do seu projeto um arquivo '.env', preenchendo as variáveis com os devidos valores;

5 - Rode a migração com "phinx migrate"
