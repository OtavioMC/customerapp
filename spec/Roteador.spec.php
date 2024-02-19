<?php

namespace customerapp\src\classes;

use customerapp\src\controllers\ClienteController;

describe('Roteador', function () {


    beforeAll( function(){
        $this->roteador = new Roteador();
        $this->controllerTeste = ClienteController::getInstancia();
        }
    );


    it('Deve criar rota get', function () {
        $this->roteador->get( "/teste", $this->controllerTeste );

        $rotas = $this->roteador->getRotas();

        $rota = $rotas[0];

        expect($rota['method'])->toBe("GET");
        expect($rota['uri'])->toBe("/teste");
        expect($rota['controller'])->toBe( $this->controllerTeste );
    });

    it('Deve criar rota get com parametros', function () {
        $uri = "/teste?tstparametro=1&tstparametro0=2";
        $uri = parse_url($uri)['path'];
        $this->roteador->get( $uri, $this->controllerTeste );

        $rotas = $this->roteador->getRotas();

        $rota = $rotas[1];

        expect($rota['method'])->toBe("GET");
        expect($rota['uri'])->toBe("/teste");
        expect($rota['controller'])->toBe($this->controllerTeste);
    });


    it('Deve criar rota post', function () {

        $this->roteador->post("/teste", $this->controllerTeste);

        $rotas = $this->roteador->getRotas();

        $rota = $rotas[2];

        expect($rota['method'])->toBe("POST");
        expect($rota['uri'])->toBe("/teste");
        expect($rota['controller'])->toBe($this->controllerTeste);
    });


    it('Deve criar rota put', function () {

        $this->roteador->put("/teste", $this->controllerTeste);

        $rotas = $this->roteador->getRotas();

        $rota = $rotas[3];

        expect($rota['method'])->toBe("PUT");
        expect($rota['uri'])->toBe("/teste");
        expect($rota['controller'])->toBe($this->controllerTeste);
    });


    it('Deve incluir rota delete', function () {
        $this->roteador->delete("/teste", $this->controllerTeste);

        $rotas = $this->roteador->getRotas();

        $rota = $rotas[4];

        expect($rota['method'])->toBe("DELETE");
        expect($rota['uri'])->toBe("/teste");
        expect($rota['controller'])->toBe($this->controllerTeste);
    });

    it('Deve incluir rota patch', function () {
        $this->roteador->patch("/teste", $this->controllerTeste);

        $rotas = $this->roteador->getRotas();

        $rota = $rotas[5];

        expect($rota['method'])->toBe("PATCH");
        expect($rota['uri'])->toBe("/teste");
        expect($rota['controller'])->toBe($this->controllerTeste);
    });

    it('Deve retornar 404 caso a rota não seja encontrada', function () {
        $this->roteador->rotear("/naoExistente", "GET");
        expect(http_response_code())->toBe(404);
    });

    //TODO: Apesar da maior parte do código ter sido feito com TDD, para adiantar resolvi pular os testes da função rotear, em uma proxima tag é extremamente necessário que sejam feitos os testes da função.

});