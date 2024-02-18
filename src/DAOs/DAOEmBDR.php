<?php

    namespace customerapp\src\DAOs;

    use customerapp\src\classes\BancoDadosRelacional;
    use customerapp\src\interfaces\DAO;
    use customerapp\src\interfaces\Singleton;
    use customerapp\src\exceptions\DAOException;

    abstract class DAOEmBDR implements DAO, Singleton {

        protected ?BancoDadosRelacional $bancoDados = null;
        protected string $nomeTabela = "";

        protected function __construct() {
            $this->bancoDados = BancoDadosRelacional::getInstancia();
        }

        abstract public static function getInstancia();

        public function buscarPorId( int $id) {
            $sql = "SELECT * FROM {$this->nomeTabela} WHERE id = :id";
            $parametros['id'] = $id;
            $dados = $this->bancoDados->buscar($sql, $parametros);
            if( ! empty( $dados ) ){
                return $this->transformarEmObjeto( array_shift($dados) );
            }else{
                throw new DAOException("O {$this->nomeTabela} não foi em encontrado. Id: " . $id);
            }
        }

        public function buscarTodos( array $parametros = [], int $pagina = 1, int $itensPorPagina = null ) {
            $sql = "SELECT * FROM {$this->nomeTabela}";

            if ( ! empty( $parametros ) ) {
                $sql .= " WHERE ";
                $condicoes = [];
                foreach ($parametros as $coluna => $valor) {
                    $condicoes[] = "$coluna = :$coluna";
                }
                $sql .= implode(" AND ", $condicoes);
            }

            if( $itensPorPagina != null ){
                $inicio = ($pagina - 1) * $itensPorPagina;
                $sql .= " LIMIT $inicio, $itensPorPagina";
            }

            return $this->transformarEmObjetos($this->bancoDados->buscar($sql, $parametros));
        }

        public function existeComId( int $id ){
            $sql = "SELECT id FROM {$this->nomeTabela} WHERE id = :id";
            $parametros['id'] = $id;
            return $this->bancoDados->existe($sql, $parametros);
        }

        public function salvar( $objeto ){
            if ( method_exists( $objeto, 'getId' ) ) {
                $iniciouTransacao = false;
                if( ! $this->bancoDados->emTransacao() ){
                    $this->bancoDados->iniciarTransacao();
                    $iniciouTransacao = true;
                }
                try{
                    if ( $this->existeComId( (int)$objeto->getId() ) ) {
                        $retorno = $this->atualizar($objeto);
                    } else {
                        $retorno =  $this->inserir($objeto);
                    }
                    if( $iniciouTransacao ){
                        $this->bancoDados->finalizarTransacao();
                    }
                    return $retorno;
                }catch( \Exception | DAOException $e ){
                    if( $iniciouTransacao ){
                        $this->bancoDados->desfazerTransacao();
                    }
                    throw new DAOException("Erro ao salvar objeto na tabela " . $this->nomeTabela . " . Erro: " . $e->getMessage() );
                    //TODO: criar log de Erros e salvar erros.
                }
            } else {
                throw new DAOException("O objeto não possui o método getId");
            }
        }

        public function excluir( int $id ) {
            $sql = "DELETE FROM {$this->nomeTabela} WHERE id = :id";
            $parametros['id'] = $id;
            $retorno = $this->bancoDados->excluir($sql, $parametros);
            if( $retorno > 0 ){
                return true;
            }else{
                throw new DAOException("O {$this->nomeTabela} não foi em encontrado para exclusão. Id: " . $id);
            }
        }

        public function transformarEmObjetos(array $corpo) {
            $objetos = [];
            foreach ($corpo as $dados) {
                $objetos[] = $this->transformarEmObjeto( is_array( $dados ) ? $dados : [] );
            }
            return $objetos;
        }

        abstract public function transformarEmObjeto(array $corpo);

        abstract protected function inserir($objeto);

        abstract protected function atualizar($objeto);
    }