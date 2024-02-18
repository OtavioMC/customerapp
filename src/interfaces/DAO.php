<?php

    namespace customerapp\src\interfaces;

    interface DAO {
        public function buscarPorId( int $id );
        public function buscarTodos( array $parametros = [], int $pagina = 1, int $itensPorPagina = null  );
        public function salvar( $objeto );
        public function excluir( int $id );
        public function transformarEmObjetos( array $corpo );
        public function transformarEmObjeto( array $corpo );
    }