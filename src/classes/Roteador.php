<?php
    namespace customerapp\src\classes;

    use customerapp\src\exceptions\RoteadorException;
    use customerapp\src\controllers\ControllerGeral;

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

            if( $uri == "/" ){
                return;
            }

            if( mb_substr($uri, -1, 1, 'UTF-8') == "/" ){
                $uri = rtrim($uri, '/');
            }

            foreach ($this->rotas as $rota) {
                $metodo = strtoupper($metodo);
                $uriRegex = str_replace("/", "\/", str_replace("/{id}", "(?:\/([0-9]+))?", $rota['uri']) );
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
                                    }else if( is_string($ultimaPartePermitidaURI) ){
                                        $primeiraParteURI = isset(explode("/", $uri)[1]) ? explode("/", $uri)[1] : null;
                                        $nomeView = $ultimaPartePermitidaURI . "-" . $primeiraParteURI;
                                        $this->redirecionarParaView( $nomeView );
                                    }
                                } else {
                                    $objetos = $controladora->buscarTodos($parametros);
                                    $resposta = [];

                                    foreach ($objetos as $objeto) {
                                        if ( method_exists($objeto, 'serializar') ) {
                                            $resposta[] = $objeto->serializar();
                                        }
                                    }

                                    http_response_code(200);
                                    echo json_encode($resposta);
                                    exit;
                                }

                            case "POST":
                                $retorno = $controladora->criar($_POST);
                                http_response_code(200);
                                echo json_encode($retorno);
                                exit;

                            case "PUT":
                                $id = isset(explode("/", $uri)[2]) ? explode("/", $uri)[2] : null;

                                if (!is_numeric($id)) {
                                    $this->redirecionarParaView(404);
                                }

                                $objeto = $controladora->buscarPorId($id);

                                if ($objeto === null) {
                                    http_response_code(404);
                                    throw new RoteadorException("Objeto não encontrado");
                                }

                                $_POST['id'] = $id;
                                $objeto = $controladora->transformarEmObjeto($_POST);
                                $retorno = $controladora->salvar($objeto);
                                http_response_code(200);
                                echo json_encode($retorno);
                                exit;

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
                        echo json_encode([
                            "success" => false,
                            "mensagem" => $e->getMessage(),
                        ]);
                        exit;
                    } catch (\Exception $e) {
                        echo json_encode([
                            "success" => false,
                            "mensagem" => "Erro ao processar requisição",
                        ]);
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