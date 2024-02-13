<?php

    namespace customerapp\src\interfaces;

    interface BancoDados {
        public function executar( string $comando, array $parametros = [] );
        public function buscar( string $comando, array $parametros = [] );
        public function inserir( string $comando, array $parametros = [] );
        public function atualizar( string $comando, array $parametros = [] );
        public function excluir( string $comando, array $parametros = [] );
        public function iniciarTransacao();
        public function finalizarTransacao();
        public function desfazerTransacao();
        public function emTransacao();
    }