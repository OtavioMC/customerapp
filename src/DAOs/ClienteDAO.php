<?php
    namespace customerapp\src\DAOs;

    use customerapp\src\DAOs\DAOEmBDR;
    use customerapp\src\models\Cliente;
    use customerapp\src\exceptions\DAOException;
    use \DateTime;

    class ClienteDAO extends DAOEmBDR{
        private static $instancia = null;
        protected string $nomeTabela = "cliente";

        public static function getInstancia() {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            return self::$instancia;
        }

        protected function parametros($cliente) {
            return [
                'id'   => $cliente->getId(),
                'nome' => $cliente->getNome(),
                'data_nascimento' => $cliente->getDataNascimento()->format('Y-m-d'),
                'email' => $cliente->getEmail(),
                'cpf' => $cliente->getCpf(),
                'senha' => password_hash( $cliente->getSenha(), PASSWORD_BCRYPT)
            ];
        }

        protected function camposValidosConsulta(): array{
            return [
                'id',
                'nome',
                'data_nascimento',
                'email',
                'cpf'
            ];
        }

        public function transformarEmObjeto(array $corpo) {
            $cliente = new Cliente();
            $cliente->setId($corpo['id']);
            $cliente->setNome($corpo['nome']);
            $cliente->setDataNascimento( new DateTime( $corpo['data_nascimento'] ) );
            $cliente->setEmail($corpo['email']);
            $cliente->setCpf($corpo['cpf']);
            return $cliente;
        }

        protected function inserir($cliente) {
            $sql = "INSERT INTO {$this->nomeTabela} (id, nome, data_nascimento, email, cpf, senha)
                        VALUES (:id, :nome, :data_nascimento, :email, :cpf, :senha)";
            $parametros = $this->parametros($cliente);
            $idInserido = $this->bancoDados->inserir($sql, $parametros);
            if($idInserido > 0){
                $cliente->setId($idInserido);
                return $cliente;
            }else{
                throw new DAOException("Erro ao salvar cliente." );
            }
        }

        protected function atualizar($cliente) {
            $mudarASenha = "";
            if( ! empty( $cliente->getSenha()  )  ) {
                $mudarASenha = ", senha = :senha ";
            }
            $sql = "UPDATE {$this->nomeTabela} SET
                        nome = :nome,
                        data_nascimento = :data_nascimento,
                        email = :email,
                        cpf = :cpf" .$mudarASenha. "
                        WHERE id = :id";
            $parametros = $this->parametros($cliente);
            if( empty( $mudarASenha ) ){
                unset($parametros['senha']);
            }
            $linhasAlteradas = $this->bancoDados->atualizar($sql, $parametros);
            if($linhasAlteradas >= 1){
                return $cliente;
            }else{
                throw new DAOException("Erro ao atualizar o cadastro. Id: " . $cliente->getId() );
            }
        }
    }