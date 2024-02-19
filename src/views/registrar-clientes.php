<?php
    require_once __DIR__ . "/../assets/header.html";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<body>
    <div class="container-xxl my-md-4 bd-layout">
        <br>
        <br>
        <br>
            <form id="registrarClientes" action="/clientes" method="post">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Senha</label>
                    <input type="senha" class="form-control" id="exampleInputPassword1">
                </div>
                <!-- <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">CPF</label>
                    <input type="cpf" class="form-control" id="cpf">
                </div> -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Concordo que por motivos do site se encontrar em desenvolvimento, vou inserir somente dados ficticios aqui por enquanto.</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous">

        // jQuery.validator.addMethod("cpf", function(value, element) {
        // value = jQuery.trim(value);

        //     value = value.replace('.','');
        //     value = value.replace('.','');
        //     cpf = value.replace('-','');
        //     while(cpf.length < 11) cpf = "0"+ cpf;
        //     var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
        //     var a = [];
        //     var b = new Number;
        //     var c = 11;
        //     for (i=0; i<11; i++){
        //         a[i] = cpf.charAt(i);
        //         if (i < 9) b += (a[i] * --c);
        //     }
        //     if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11-x }
        //     b = 0;
        //     c = 11;
        //     for (y=0; y<10; y++) b += (a[y] * c--);
        //     if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }

        //     var retorno = true;
        //     if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) retorno = false;

        //     return this.optional(element) || retorno;

        // }, "Informe um CPF válido");


        // $(document).ready(function(){

        //     $("#registrarClientes").validate({
        //         rules: {
        //             cpf: {cpf: true, required: true}
        //         },
        //         messages: {
        //             cpf: { cpf: 'CPF inválido'}
        //         }
        //         ,submitHandler:function(form) {
        //             alert('ok');
        //         }
        //     });
        // });
    </script>

    <script src="\vendor\twbs\bootstrap\dist\js\bootstrap.bundle.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
</body>