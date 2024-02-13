<?php

namespace customerapp\src\services;

use customerapp\src\interfaces\Controller;
use customerapp\src\interfaces\Singleton;

abstract class ControllerGeral implements Controller, Singleton{
    protected $service;

    protected function __construct() {
        $nomeClasseService = str_replace('Controller', '' , get_class($this) ) . "Service";
        $this->service = call_user_func( $nomeClasseService . "::getInstancia()");
    }

    abstract public static function getInstancia();

    public function buscarPorId($id) {
        return $this->service->buscarPorId($id);
    }

    public function buscarTodos(array $parametros = [], int $pagina = 1, int $itensPorPagina = null) {
        return $this->service->buscarTodos( $parametros, $pagina, $itensPorPagina);
    }

    public function salvar($objeto) {
        return $this->service->salvar($objeto);
    }

    public function excluir($id) {
        return $this->service->excluir($id);
    }

    public function transformarEmObjetos(Array $corpo) {
        return $this->service->transformarEmObjetos($corpo);
    }

    public function transformarEmObjeto(Array $corpo) {
        return $this->service->transformarEmObjeto($corpo);
    }

    abstract public function criar( Array $corpo );
}