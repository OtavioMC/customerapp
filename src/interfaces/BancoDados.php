<?php

    namespace customerapp\src\interfaces;

    interface BancoDados {
        public function executar($comando, $parametros = []);
        public function buscar($comando, $parametros = []);
        public function inserir($comando, $parametros = []);
        public function atualizar($comando, $parametros = []);
        public function excluir($comando, $parametros = []);
        public function iniciarTransacao();
        public function finalizarTransacao();
        public function desfazerTransacao();
    }