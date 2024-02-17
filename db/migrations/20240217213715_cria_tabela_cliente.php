<?php

    declare(strict_types=1);

    use Phinx\Migration\AbstractMigration;

    final class CriaTabelaCliente extends AbstractMigration{
        function up(){
            $sql = <<<'SQL'
                    CREATE TABLE cliente (
                    id INT NOT NULL AUTO_INCREMENT,
                    nome VARCHAR(300) NOT NULL,
                    cpf VARCHAR(14) NOT NULL UNIQUE,
                    email VARCHAR(200) NOT NULL UNIQUE,
                    data_nascimento DATE NOT NULL,
                    senha VARCHAR(60) NOT NULL,
                    PRIMARY KEY ( id )
                    ) ENGINE=INNODB
                    SQL;
            $this->execute( $sql );
        }

        function down(){
            $this->execute( 'DROP TABLE cliente' );
        }

    }
