<?php

namespace customerapp\src\services;

use customerapp\src\exceptions\ServiceException;
use customerapp\src\models\Cliente;
use DateTime;

describe('ClienteService', function () {

    it('Deve fornecer uma instância adequada', function () {
        $ClienteService = ClienteService::getInstancia();
        expect($ClienteService)->toBeAnInstanceOf(ClienteService::class);
    });

    beforeAll(function () {
        $this->service = ClienteService::getInstancia();
    });

    it('Deve passar um cliente na validação caso não haja nenhum problema', function () {
        $cliente = new Cliente();
        $cliente->setId(0);
        $cliente->setNome('Teste Silva Teste');
        $dataNascimentoTeste = "01/01/2001";
        $dataNascimentoTeste = new DateTime($dataNascimentoTeste);
        $cliente->setDataNascimento($dataNascimentoTeste);
        $cliente->setEmail('testesilvateste@gmail.com');
        $cliente->setCpf('966.042.870-76');
        $cliente->setSenha("123456");

        $erros = array();

        $validado = $this->service->validar( $cliente, $erros);

        expect($validado)->toBeTruthy();

        expect( $erros )->toBeEmpty();

    });


    it('Deve lançar exceção caso a instância do cliente não esteja devidamente preenchida', function () {

        expect(function(){
            $cliente =  "";
            $erros = [];
            $this->service->validar( $cliente, $erros);
        })->toThrow( new ServiceException() );

    });

    it('Deve lançar exceção caso o nome seja inválido', function () {

        expect(function(){
            $cliente = new Cliente();
            $cliente->setId(0);
            $cliente->setNome('Teste');
            $dataNascimentoTeste = "01/01/2001";
            $dataNascimentoTeste = new DateTime($dataNascimentoTeste);
            $cliente->setDataNascimento($dataNascimentoTeste);
            $cliente->setEmail('testesilvateste@gmail.com');
            $cliente->setCpf('966.042.870-76');
            $cliente->setSenha("123456");
            $this->service->validar( $cliente);
        })->toThrow( new ServiceException() );

    });

    it('Deve lançar exceção caso a data de nascimento seja inválida', function () {

        expect(function(){
            $cliente = new Cliente();
            $cliente->setId(0);
            $cliente->setNome('Teste da Silva');
            $dataNascimentoTeste = "01/12/9524";
            $dataNascimentoTeste = new DateTime($dataNascimentoTeste);
            $cliente->setDataNascimento($dataNascimentoTeste);
            $cliente->setEmail('testesilvateste@gmail.com');
            $cliente->setCpf('966.042.870-76');
            $cliente->setSenha("123456");
            $this->service->validar( $cliente);
        })->toThrow( new ServiceException() );

    });


    it('Deve lançar exceção caso o email seja inválido', function () {

        expect(function(){
            $cliente = new Cliente();
            $cliente->setId(0);
            $cliente->setNome('Teste da Silva');
            $dataNascimentoTeste = "01/12/2000";
            $dataNascimentoTeste = new DateTime($dataNascimentoTeste);
            $cliente->setDataNascimento($dataNascimentoTeste);
            $cliente->setEmail('testesilvateste@!gmail.com');
            $cliente->setCpf('966.042.870-76');
            $cliente->setSenha("123456");
            $this->service->validar( $cliente);
        })->toThrow( new ServiceException() );

    });


    it('Deve lançar exceção caso o cpf seja inválido(formato próximo a um válido)', function () {

        expect(function(){
            $cliente = new Cliente();
            $cliente->setId(0);
            $cliente->setNome('Teste da Silva');
            $dataNascimentoTeste = "01/12/2000";
            $dataNascimentoTeste = new DateTime($dataNascimentoTeste);
            $cliente->setDataNascimento($dataNascimentoTeste);
            $cliente->setEmail('testesilvateste@gmail.com');
            $cliente->setCpf('966.942.870-76');
            $cliente->setSenha("123456");
            $this->service->validar( $cliente);
        })->toThrow( new ServiceException() );
    });

    it('Deve lançar exceção caso o cpf seja inválido(fora do formato exigido)', function () {

        expect(function(){
            $cliente = new Cliente();
            $cliente->setId(0);
            $cliente->setNome('Teste da Silva');
            $dataNascimentoTeste = "01/12/2000";
            $dataNascimentoTeste = new DateTime($dataNascimentoTeste);
            $cliente->setDataNascimento($dataNascimentoTeste);
            $cliente->setEmail('testesilvateste@gmail.com');
            $cliente->setCpf('96604287076');
            $cliente->setSenha("123456");
            $this->service->validar( $cliente);
        })->toThrow( new ServiceException() );
    });


    it('Deve lançar exceção caso a senha seja muito curta', function () {

        expect(function(){
            $cliente = new Cliente();
            $cliente->setId(0);
            $cliente->setNome('Teste Silva Teste');
            $dataNascimentoTeste = "01/01/2001";
            $dataNascimentoTeste = new DateTime($dataNascimentoTeste);
            $cliente->setDataNascimento($dataNascimentoTeste);
            $cliente->setEmail('testesilvateste@gmail.com');
            $cliente->setCpf('966.042.870-76');
            $cliente->setSenha("123");
            $this->service->validar( $cliente);
        })->toThrow( new ServiceException() );
    });


});