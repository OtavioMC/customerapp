<?php

    namespace customerapp\src\controllers;

    use customerapp\src\exceptions\ControllerException;
    use customerapp\src\interfaces\Controller;
    use customerapp\src\interfaces\Singleton;

    abstract class ControllerGeral implements Controller, Singleton{
        protected $service;

        protected function __construct() {
            $nomeClasseService = str_replace('Controller', '' , get_class($this) ) . "Service";
            $nomeClasseService = str_replace( "controllers", "services", $nomeClasseService );
            $this->service = $nomeClasseService::getInstancia();
        }

        abstract public static function getInstancia();

        public function buscarPorId($id) {
            return $this->service->buscarPorId($id);
        }

        public function buscarTodos(array $parametros = [], int $pagina = 1, int $itensPorPagina = null) {
            return $this->service->buscarTodos( $parametros, $pagina, $itensPorPagina);
        }

        public function salvar($objeto, &$erros = []) {
            return $this->service->salvar($objeto, $erros);
        }

        public function excluir($id) {
            return $this->service->excluir($id);
        }

        public function transformarEmObjetos(array $corpo) {
            return $this->service->transformarEmObjetos($corpo);
        }

        public function transformarEmObjeto(array $corpo) {
            return $this->service->transformarEmObjeto($corpo);
        }

        abstract public function criar( array $corpo, array &$erros = [] );

        public function verificarEnvio( array $corpo, array $camposPadroes ){
            foreach( $camposPadroes as $campoPadrao ){
                if( ! array_key_exists($campoPadrao, $corpo) ){
                    throw new ControllerException( "O " . $campoPadrao . " não foi enviado!" );
                }
            }
        }

        public function regularizarEnvio( array $corpo ): array{
            $novoCorpo = array();
            foreach( $corpo as $campo => $valor ){
                $novoCorpo[$campo] = $this->service->regularizarCampo( $valor );
            }
            return $novoCorpo;
        }

    }