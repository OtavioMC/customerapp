<?php


    namespace customerapp\src\controllers;
    use customerapp\src\exceptions\controllerException;
    use customerapp\src\models\Cliente;
    use DateTime;

    describe('ClienteController', function () {

        it('Deve fornecer uma instância adequada', function () {
            $ClienteController = ClienteController::getInstancia();
            expect($ClienteController)->toBeAnInstanceOf(ClienteController::class);
        });

        beforeAll(function () {
            $this->controller = ClienteController::getInstancia();
        });

        it('Deve criar um cliente e excluir-lo', function () {
            $_POST['nome'] = "Teste Silva Teste";
            $_POST['data_nascimento'] = "01/01/2001";
            $_POST['email'] = "testesilvateste2@gmail.com";
            $_POST['cpf'] = "989.059.650-40";
            $_POST['senha'] = "123456";

            $erros = array();

            $cliente = $this->controller->criar( $_POST );

            expect($cliente)->toBeAnInstanceOf( Cliente::class );

            expect( $erros )->toBeEmpty();

            $retorno = $this->controller->excluir($cliente->getId());

            expect( $retorno )->toBeTruthy();

        });


        it('Deve proteger a aplicação de injeção de scripts e outros ataques', function () {

            $_POST['nome'] =  "<?php sleep(910902109210921091209120909129021012990210912) Teste Silva Teste";
            $_POST['data_nascimento'] = "<b>01/01/2001</b>";
            $_POST['email'] = "<?php die() testesilvateste2@gmail.com";
            $_POST['cpf'] = "<br>989.059.650-40";
            $_POST['senha'] = "<?php die(); ?>123456";

            $corpo = $this->controller->regularizarEnvio( $_POST );

            expect($corpo['nome'])->toBe("&lt;?php sleep(910902109210921091209120909129021012990210912) Teste Silva Teste");
            expect($corpo['data_nascimento'])->toBe("&lt;b&gt;01/01/2001&lt;/b&gt;");
            expect($corpo['email'])->toBe("&lt;?php die() testesilvateste2@gmail.com");
            expect($corpo['cpf'])->toBe("&lt;br&gt;989.059.650-40");
            expect($corpo['senha'])->toBe("&lt;?php die(); ?&gt;123456");



        });





    });