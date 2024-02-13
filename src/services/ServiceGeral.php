<?php

namespace customerapp\src\services;

use customerapp\src\interfaces\Service;
use customerapp\src\interfaces\Singleton;

abstract class ServiceGeral implements Service, Singleton{
    protected $dao;

    protected function __construct() {
        $nomeClasseDAO = str_replace('Service', '' , get_class($this) ) . "DAO";
        $this->dao = call_user_func( $nomeClasseDAO . "::getInstancia()");
    }

    abstract public static function getInstancia();

    public function buscarPorId($id) {
        return $this->dao->buscarPorId($id);
    }

    public function buscarTodos(array $parametros = [], int $pagina = 1, int $itensPorPagina = null) {
        return $this->dao->buscarTodos( $parametros, $pagina, $itensPorPagina);
    }

    public function salvar($objeto) {
        $this->validar($objeto);
        return $this->dao->salvar($objeto);
    }

    public function excluir($id) {
        return $this->dao->excluir($id);
    }

    public function transformarEmObjetos(Array $corpo) {
        return $this->dao->transformarEmObjetos($corpo);
    }

    public function transformarEmObjeto(Array $corpo) {
        return $this->dao->transformarEmObjeto($corpo);
    }

    abstract public function validar( $objeto );
}