<?php

namespace customerapp\src\DAOs;
use customerapp\src\models\Cliente;
use DateTime;

describe('ClienteDAO', function () {

    it('Deve fornecer uma instância adequada', function () {
        $clienteDAO = ClienteDAO::getInstancia();
        expect($clienteDAO)->toBeAnInstanceOf(ClienteDAO::class);
    });

    beforeAll(function () {
        $this->dao = ClienteDAO::getInstancia();
        $this->idInserido = array();

    });

    it('Deve salvar adequadamente um cliente', function () {
        $cliente = new Cliente();
        $cliente->setId(0);
        $cliente->setNome('Teste Silva Teste');
        $dataNascimentoTeste = "01/01/2001";
        $dataNascimentoTeste = new DateTime($dataNascimentoTeste);
        $cliente->setDataNascimento($dataNascimentoTeste);
        $cliente->setEmail('testesilvateste@gmail.com');
        $cliente->setCpf('000.000.000-00');
        $cliente->setSenha("123456");

        $clienteSalvo = $this->dao->salvar($cliente);

        expect($clienteSalvo->getId())->toBeGreaterThan(0);
        $this->idInserido[] = $clienteSalvo->getId();
    });

    it('Deve obter adequadamente todos os clientes', function () {
        $clientes = $this->dao->buscarTodos([]);
        expect(count($clientes))->toBeGreaterThan(0);
    });

    it('Deve obter adequadamente um cliente com o id', function () {
        $id = $this->idInserido[0];
        $cliente = $this->dao->buscarPorId($id);

        expect ($cliente)->toBeAnInstanceOf(Cliente::class);
    });

    it('Deve alterar um cliente devidamente', function () {
        $id = $this->idInserido[0];
        $cliente = $this->dao->buscarPorId($id);
        $novoNome = "Teste Teste Jr.";
        $novoEmail = "agoraeoutroteste@gmail.com";
        $novoCPF = "123.456.789-10";
        $novaDataNascimento = new DateTime("01/02/2023");
        $cliente->setNome($novoNome);
        $cliente->setEmail($novoEmail);
        $cliente->setCpf($novoCPF);
        $cliente->setDataNascimento($novaDataNascimento);

        $this->dao->salvar($cliente);

        $clienteAtualizado = $this->dao->buscarPorId($id);

        expect ( $clienteAtualizado )->toBeAnInstanceOf(Cliente::class);
        expect ( $clienteAtualizado->getNome() )->toBe( $cliente->getNome() );
        expect ( $clienteAtualizado->getEmail() )->toBe( $cliente->getEmail() );
        expect ( $clienteAtualizado->getCpf() )->toBe( $cliente->getCpf() );
        expect ( $clienteAtualizado->getDataNascimento()->format("d-m-Y") )->toBe( $cliente->getDataNascimento()->format("d-m-Y") );
    });

    it("Deve excluir um cliente com o id", function(){
        $id = $this->idInserido[0];
        $retorno = $this->dao->excluir($id);
        expect( $retorno )->toBeTruthy();
    });


});