<?php

    namespace customerapp\src\interfaces;

    interface Service{
        public function buscarPorId( int $id );
        public function buscarTodos( array $parametros = [], int $pagina = 1, int $itensPorPagina = null  );
        public function salvar( $objeto );
        public function excluir( int $id );
        public function transformarEmObjetos( Array $corpo );
        public function transformarEmObjeto( Array $corpo );
        public function validar( $objeto, array &$erros = []);
    }