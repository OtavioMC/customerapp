<?php
    namespace customerapp\src\classes;

    use customerapp\src\exceptions\RoteadorException;
    use customerapp\src\controllers\ControllerGeral;
use customerapp\src\exceptions\ControllerException;

    class Roteador{
        private array $rotas = array();


        public function get($uri, ControllerGeral $controller, $parametros = array()){
            $rota = [
                "uri" => $uri,
                "controller" => $controller,
                "method" => "GET"
            ];

            if( ! empty($parametros) ){
                foreach($parametros as  $chave => $parametro){
                    $rota[$chave] = $parametro;
                }
            }

            $this->rotas[]  = $rota;
        }

        public function post($uri, $controller){
            $this->rotas[] = [
                "uri" => $uri,
                "controller" => $controller,
                "method" => "POST"
            ];
        }

        public function put($uri, $controller){
            $this->rotas[] = [
                "uri" => $uri,
                "controller" => $controller,
                "method" => "PUT"
            ];
        }

        public function patch($uri, $controller){
            $this->rotas[] = [
                "uri" => $uri,
                "controller" => $controller,
                "method" => "PATCH"
            ];
        }

        public function delete($uri, $controller){
            $this->rotas[] = [
                "uri" => $uri,
                "controller" => $controller,
                "method" => "DELETE"
            ];
        }

        public function getRotas(){
            return $this->rotas;
        }

        //TODO: Refatorar switch interno com métodos privados, criar classe de RespostaHttp .
        public function rotear($uri, $metodo, $parametros = []){
            global $_;

            if( $uri == "/" ){
                return;
            }

            if( mb_substr($uri, -1, 1, 'UTF-8') == "/" ){
                $uri = rtrim($uri, '/');
            }

            foreach ($this->rotas as $rota) {
                $metodo = strtoupper($metodo);
                $uriRegex = str_replace("/", "\/", str_replace("/{id}", "(?:/([0-9]+))?", $rota['uri']) );
                if (preg_match("#^{$uriRegex}$#", $uri) && $metodo == $rota['method']) {
                    $controladora =   $rota['controller'];

                    try {
                        switch ($metodo) {
                            case "GET":
                                $ultimaPartePermitidaURI = isset(explode("/", $uri)[2]) ? explode("/", $uri)[2] : null;

                                if ( !empty( $ultimaPartePermitidaURI )){
                                    if( is_numeric($ultimaPartePermitidaURI) ){
                                        $objeto = $controladora->buscarPorId($ultimaPartePermitidaURI);

                                        if ($objeto !== null && method_exists($objeto, 'serializar')) {
                                            $resposta = $objeto->serializar();
                                            http_response_code(200);
                                            echo json_encode($resposta);
                                            exit;
                                        } else {
                                            $resposta = [];
                                            http_response_code(404);
                                            echo json_encode($resposta);
                                            exit;
                                        }
                                    }else{
                                        $primeiraParteURI = isset(explode("/", $uri)[1]) ? explode("/", $uri)[1] : null;
                                        $nomeView = $ultimaPartePermitidaURI . "-" . $primeiraParteURI;
                                        if($ultimaPartePermitidaURI != "listar"){
                                            $this->redirecionarParaView( $nomeView );
                                        }else{
                                            global $objetos;
                                            $objetos = $controladora->buscarTodos($parametros);
                                            http_response_code(200);
                                            $this->redirecionarParaView($nomeView);
                                            exit;
                                        }
                                    }
                                } else {
                                    global $objetos;
                                    $objetos = $controladora->buscarTodos($parametros);
                                    $primeiraParteURI = isset(explode("/", $uri)[1]) ? explode("/", $uri)[1] : null;
                                    $nomeView = "listar-" . $primeiraParteURI;
                                    http_response_code(200);
                                    $this->redirecionarParaView($nomeView);
                                    exit;
                                }

                            case "POST":
                                //TODO: melhorar lançamento de erro para CPF e Email duplicados, adicionando verificações na DAO.
                                try{
                                    $objeto = $controladora->criar($_POST);
                                }catch(ControllerException $e){
                                    throw new RoteadorException($e->getMessage());
                                }catch( \Exception $e ){
                                    http_response_code(500);
                                    echo json_encode("CPF ou Email ja cadastrados!");
                                    exit;
                                }

                                if ($objeto !== null && method_exists($objeto, 'serializar')) {
                                    $resposta = $objeto->serializar();
                                    http_response_code(200);
                                    echo json_encode($resposta);
                                    exit;
                                } else {
                                    $resposta = [];
                                    http_response_code(500);
                                    echo json_encode($resposta);
                                    exit;
                                }
                                exit;

                            case "PUT":
                                $id = isset(explode("/", $uri)[2]) ? explode("/", $uri)[2] : null;

                                if (!is_numeric($id)) {
                                    http_response_code(404);
                                    throw new RoteadorException("Id não encontrado");
                                }

                                $objeto = $controladora->buscarPorId($id);

                                if ($objeto === null) {
                                    http_response_code(404);
                                    throw new RoteadorException("Objeto não encontrado");
                                }


                                if( isset($_POST) ){
                                    $_POST['id'] = $id;
                                    $objeto = $controladora->transformarEmObjeto($_POST);
                                    $retorno = $controladora->salvar($objeto);
                                    http_response_code(200);
                                    echo json_encode($retorno);
                                    exit;
                                }else{
                                    http_response_code(404);
                                    throw new RoteadorException("Dados enviados incorretamente!");
                                }

                            case "DELETE":
                                $id = isset(explode("/", $uri)[2]) ? explode("/", $uri)[2] : null;
                                if (!is_numeric($id)) {
                                    http_response_code(404);
                                    throw new RoteadorException("Registro não encontrado para a exclusão!");
                                }
                                $retorno = $controladora->excluir($id);

                                echo json_encode($retorno);
                                exit;
                        }
                    } catch (RoteadorException $e) {
                        http_response_code(400);
                        echo $e->getMessage();
                        exit;
                    } catch (\Exception $e) {
                        $this->redirecionarParaView(500);
                        exit;
                    }
                }
            }
            $this->redirecionarParaView(404);
        }

        public function redirecionarParaView( $codigo, $ehHttp = true ){
            if($ehHttp){
                http_response_code( (int)$codigo );
            }
            require_once  __DIR__ . "/../views/" . $codigo . ".php";
            exit;
        }


    }