<?php

    namespace customerapp\src\services;

    use customerapp\src\interfaces\Service;
    use customerapp\src\interfaces\Singleton;
    use customerapp\src\DAOs\ClienteDAO;

    abstract class ServiceGeral implements Service, Singleton{
        protected $dao;

        protected function __construct() {
            $nomeClasseDAO = str_replace('Service', '' , $this::class ) . "DAO";
            $nomeClasseDAO = str_replace("services", "DAOs", $nomeClasseDAO );
            $this->dao = $nomeClasseDAO::getInstancia();
        }

        abstract public static function getInstancia();

        public function buscarPorId($id) {
            return $this->dao->buscarPorId($id);
        }

        public function buscarTodos(array $parametros = [], int $pagina = 1, int $itensPorPagina = null) {
            return $this->dao->buscarTodos( $parametros, $pagina, $itensPorPagina);
        }

        public function salvar($objeto, &$erros = [] ) {
            $this->validar($objeto, $erros);
            return $this->dao->salvar($objeto);
        }

        public function excluir($id) {
            return $this->dao->excluir($id);
        }

        public function transformarEmObjetos(array $corpo) {
            return $this->dao->transformarEmObjetos($corpo);
        }

        public function transformarEmObjeto(array $corpo) {
            return $this->dao->transformarEmObjeto($corpo);
        }

          public function regularizarCampo($campo) {
            if (is_array($campo)) {
              return array_map(  [$this, 'regularizarCampo'], $campo);
            } elseif (is_object($campo)) {
              $data = serialize($campo);
              return unserialize($data, ['allowed_classes' => [$campo::class]]);
            } else {
              return htmlspecialchars($campo, ENT_QUOTES, 'UTF-8');
            }
          }

        abstract public function validar( $objeto, array &$erros = []);
    }