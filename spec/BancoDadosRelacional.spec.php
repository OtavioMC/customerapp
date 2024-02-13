<?php

namespace customerapp\src\classes;

describe('BancoDadosRelacional', function () {

    it('Deve estabelecer uma conexão bem-sucedida com o mysql', function () {
        $bancoDados = BancoDadosRelacional::getInstancia();
        expect($bancoDados)->toBeAnInstanceOf(BancoDadosRelacional::class);
    });
});