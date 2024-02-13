<?php

    namespace customerapp\src\interfaces;

    interface DAO {
        public function buscarPorId($id);
        public function buscarTodos();
        public function salvar($objeto);
        public function excluir($id);
        public function transformarEmObjetos( Array $corpo );
        public function transformarEmObjeto( Array $corpo );
    }