<?php
    namespace customerapp\src\classes;

    use customerapp\src\exceptions\BDRException;
    use \PDO;
    use \PDOException;
    use customerapp\src\interfaces\BancoDados;
    use customerapp\src\interfaces\Singleton;

    require_once __DIR__ . "/../../config.php";

    class BancoDadosRelacional implements BancoDados, Singleton{

        private static $instancia;
        private string $host = "";
        private string $nomeBanco = "";
        private string $usuario = "";
        private string $senha = "";
        private ?PDO $conexaoPDO = null;

        private function __construct() {
            $this->host = $_ENV['DB_HOST'];
            $this->nomeBanco = $_ENV['DB_NAME'];
            $this->usuario = $_ENV['DB_USER'];
            $this->senha = $_ENV['DB_PASSWORD'];
            $this->conexaoPDO = $this->conectar();
        }

        public static function getInstancia() {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            return self::$instancia;
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
                $mensagemErro =  'Erro de conexão: ' . $e->getMessage();
                throw new BDRException($mensagemErro);
                //TODO: Criar e manter salvo log de erros no BD posteriormente.
            }

            return $this->conexaoPDO;
        }

        public function executar( string $sql,  array $parametros = []) {
            $stmt = $this->conexaoPDO->prepare($sql);
            $stmt->execute($parametros);
            return $stmt;
        }

        public function buscar( string $sql, array $parametros = [] ) {
            $stmt = $this->executar($sql, $parametros);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function inserir( string $sql, array $parametros = [] ) {
            $stmt = $this->executar($sql, $parametros);
            return $this->conexaoPDO->lastInsertId();
        }

        public function atualizar( string $sql,  array $parametros = [] ) {
            $stmt = $this->executar($sql, $parametros);
            return $stmt->rowCount();
        }

        public function excluir( string $sql, array $parametros = [] ) {
            $stmt = $this->executar($sql, $parametros);
            return $stmt->rowCount();
        }

        public function existe( string $sql,  array $parametros ) {
            $stmt = $this->conexaoPDO->prepare($sql);
            $stmt->execute($parametros);

            return $stmt->rowCount() > 0;
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

        public function emTransacao(){
            return $this->conexaoPDO->inTransaction();
        }
    }
