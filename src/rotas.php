<?php

    require_once __DIR__ . '/../vendor/autoload.php';
    use customerapp\src\classes\Roteador;
    use customerapp\src\controllers\ClienteController;

    $roteador = new Roteador();

    // Rotas para Clientes===>

    //Rotas para Controller
    $roteador->get("/clientes", ClienteController::getInstancia());
    $roteador->get("/clientes/{id}", ClienteController::getInstancia()); // Rota para buscar por ID
    $roteador->post("/clientes", ClienteController::getInstancia()); //Requisicao para enviar
    $roteador->put("/clientes/{id}", ClienteController::getInstancia()); // Rota para atualizar por ID
    $roteador->delete("/clientes/{id}", ClienteController::getInstancia()); // Rota para excluir por ID

    //Rotas para view
    $roteador->get("/clientes/registrar", ClienteController::getInstancia());

    // <==== Fim rotas para Clientes.

    $url = parse_url( $_SERVER['REQUEST_URI'] );

    $uri = $url['path'] ?? "/";
    $parametros = $url['query'] ?? [];
    $metodo = $_POST["_METHOD"] ?? $_SERVER['REQUEST_METHOD'];
    $roteador->rotear($uri, $metodo, $parametros );
