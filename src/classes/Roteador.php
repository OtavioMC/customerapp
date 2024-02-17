<?php
    namespace customerapp\src\classes;


    class Roteador{
        private array $rotas = array();


        public function get($uri, $controller, $parametros = array()){
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

        public function rotear( $uri, $metodo, $parametros = array() ){
            global $_;
            foreach( $this->rotas as $rota ){
                if( $uri == $rota['uri'] && strtoupper($metodo) == $rota['method'] ){
                    require_once "/src/controllers/" . $rota['controller'] . '.php';
                    $controladora = new $rota['controller'];

                    //TODO: Acertar o roteamento e as respostas http
                    // if( $metodo == "GET" && preg_match('^/[a-zA-Z]+/[0-9]+$', $uri ) ){
                    //     $id = explode("/", $uri)[2];
                    //     global $objeto;
                    //     $objeto = $controladora->obterComId( $id );
                    // }else if( $metodo == "GET" ){
                    //     global $objetos;
                    //     $objetos = $controladora->obterTodos( $parametros );
                    // }else if( $metodo == "POST" || $metodo == "PUT"){
                    //     $objeto = $controladora->criar($_POST);
                    //     return $controladora->salvar($objeto);
                    // }else if( $metodo == "DELETE" && preg_match('^/[a-zA-Z]+/[0-9]+$', $uri ) ){
                    //     $id = explode("/", $uri)[2];
                    //     return $controladora->excluir($id);
                    // }
                }
            }
            $this->paginaHttp(404);
        }

        public function paginaHttp( $codigo ){
            http_response_code( (int)$codigo );
            require_once  __DIR__ . "/../views/" . $codigo . ".php";
        }


    }