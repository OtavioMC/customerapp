<?php
    namespace customerapp\src\classes;
    use \PDO;
    use \PDOException;

    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ .  '/../../');
    $dotenv->load();

    class BancoDados {

        private $host = "";
        private $nomeBanco = "";
        private $usuario = "";
        private $senha = "";
        private $conexaoPDO;
    
        public function __construct() {
            $this->host = $_ENV['DB_HOST'];
            $this->nomeBanco = $_ENV['DB_NAME'];
            $this->usuario = $_ENV['DB_USER'];
            $this->senha = $_ENV['DB_PASSWORD'];
            $this->conexaoPDO = $this->conectar();
        }
    
        private function conectar() {
            $this->conexaoPDO = null;
    
            try {
                $this->conexaoPDO = new PDO(
                    "mysql:host={$this->host};dbname={$this->nomeBanco}",
                    $this->usuario,
                    $this->senha
                );
    
                $this->conexaoPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo 'Erro de conexão: ' . $e->getMessage();
                throw $e;

                //TODO: Criar e manter salvo log de erros no BD posteriormente.
            }
    
            return $this->conexaoPDO;
        }
    
        public function executar($sql, $parametros = []) {
            $stmt = $this->conexaoPDO->prepare($sql);
            $stmt->execute($parametros);
            return $stmt;
        }
    
        public function selecionar($sql, $parametros = []) {
            $stmt = $this->executar($sql, $parametros);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
        public function inserir($sql, $parametros = []) {
            $stmt = $this->executar($sql, $parametros);
            return $this->conexaoPDO->lastInsertId();
        }
    
        public function atualizar($sql, $parametros = []) {
            $stmt = $this->executar($sql, $parametros);
            return $stmt->rowCount();
        }
    
        public function excluir($sql, $parametros = []) {
            $stmt = $this->executar($sql, $parametros);
            return $stmt->rowCount();
        }
    
        public function iniciarTransacao() {
            $this->conexaoPDO->beginTransaction();
        }
    
        public function finalizarTransacao() {
            $this->conexaoPDO->commit();
        }
    
        public function desfazerTransacao() {
            $this->conexaoPDO->rollBack();
        }
    }
