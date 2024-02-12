<?php

namespace customerapp\src\classes;

describe('BancoDados', function () {

    it('deve estabelecer uma conexão bem-sucedida', function () {
        $bancoDados = new BancoDados();
        expect($bancoDados)->toBeAnInstanceOf(BancoDados::class);
    });
});