<?php

    require_once  __DIR__ . "/classes/Roteador.php";
    use customerapp\src\classes\Roteador;

    $roteador = new Roteador();

    // Rotas para Clientes
    $roteador->get("/clientes", "ClienteController");
    $roteador->get("/clientes/{id}", "ClienteController"); // Rota para buscar por ID
    $roteador->post("/clientes", "ClienteController"); //Requisicao para enviar
    $roteador->put("/clientes/{id}", "ClienteController"); // Rota para atualizar por ID
    $roteador->delete("/clientes/{id}", "ClienteController"); // Rota para excluir por ID

    $url = parse_url( $_SERVER['REQUEST_URI'] );

    $uri = $url['path'] ?? "/";
    $parametros = $url['query'] ?? [];
    $metodo = $_POST["_METHOD"] ?? $_SERVER['REQUEST_METHOD'];
    $roteador->rotear($uri, $metodo, $parametros );

    //TODO: Inserir as rotas disponíveis e rotear para a rota requisitada utilizando a classe Roteador.