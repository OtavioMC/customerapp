<?php

    namespace customerapp\src\DAOs;

    use customerapp\src\classes\BancoDadosRelacional;
    use customerapp\src\interfaces\DAO;
    use customerapp\src\interfaces\Singleton;
    use DAOException;

    abstract class DAOEmBDR implements DAO, Singleton {

        protected $bancoDados;
        protected $nomeTabela;

        private function __construct() {
            $this->bancoDados = BancoDadosRelacional::getInstancia();
        }

        abstract public static function getInstancia();

        public function buscarPorId($id) {
            $sql = "SELECT * FROM {$this->nomeTabela} WHERE id = :id";
            $parametros['id'] = $id;
            return $this->transformarEmObjeto( array_shift($this->bancoDados->buscar($sql, $parametros)) );
        }

        public function buscarTodos() {
            $sql = "SELECT * FROM {$this->nomeTabela}";
            return $this->transformarEmObjetos( $this->bancoDados->buscar($sql) );
        }

        public function existeComId($id){
            $sql = "SELECT id FROM {$this->nomeTabela} WHERE id = ?";
            $parametros['id'] = $id;
            return $this->bancoDados->existe($sql, $parametros);
        }

        public function salvar($objeto){
            if (method_exists($objeto, 'getId')) {
                if ( $this->existeComId($objeto->getId()) ) {
                    return $this->atualizar($objeto);
                } else {
                    return $this->inserir($objeto);
                }
            } else {
                throw new DAOException("O objeto não possui o método getId");
            }
        }

        public function excluir($id) {
            $sql = "DELETE FROM {$this->nomeTabela} WHERE id = :id";
            $parametros['id'] = $id;
            return $this->bancoDados->excluir($sql, $parametros);
        }

        public function transformarEmObjetos(Array $corpo) {
            $objetos = [];
            foreach ($corpo as $dados) {
                $objetos[] = $this->transformarEmObjeto($dados);
            }
            return $objetos;
        }

        abstract public function transformarEmObjeto(Array $corpo);

        abstract protected function inserir($objeto);

        abstract protected function atualizar($objeto);
    }