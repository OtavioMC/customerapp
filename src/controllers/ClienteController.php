<?php

    namespace customerapp\src\controllers;

    use customerapp\src\exceptions\ControllerException;
    use customerapp\src\exceptions\ServiceException;

    class ClienteController extends ControllerGeral{
        private static $instancia = null;

        public static function getInstancia() {
            if (self::$instancia === null) {
                self::$instancia = new self();
            }
            return self::$instancia;
        }

        public function criar( array $corpo, array &$erros = [] ){

            $corpo['id'] = 0;

            $camposPadrao = array(
                "id", "nome", "data_nascimento", "email", "cpf", "senha"
            );
            try{
                $this->verificarEnvio($corpo,  $camposPadrao);
                $corpo = $this->regularizarEnvio($corpo);
                $cliente = $this->transformarEmObjeto( $corpo );
                $cliente->setSenha($_POST['senha']);
                return  $this->salvar($cliente, $erros);

            }catch( ControllerException $e ){
                throw new ControllerException( "Erro ao criar cliente. " . $e->getMessage()  );
            }catch( ServiceException $e ){
                throw new ControllerException( "Erro ao criar cliente. " . $e->getMessage()  );
            } catch( \Exception $e ){
                throw $e;
            }
        }


    }