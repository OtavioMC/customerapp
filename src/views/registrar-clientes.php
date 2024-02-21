<?php
    require_once __DIR__ . "/../assets/header.html";
    //TODO: melhorar validação dos campos pelo jquery.
?>

<!DOCTYPE html>
<html lang="pt-BR">

<body>
    <br>
    <div class="container-xxl my-md-4 bd-layout">
        <form id="registrarCliente" action="/clientes" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" minlength="10" maxlength="280" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-3">
                <div class="col-auto">
                    <label for="senha" class="form-label">Senha</label>
                </div>
                <div class="col-auto">
                    <input type="password" id="senha" class="form-control" maxlength="20" minlength="6"
                        aria-describedby="passwordHelpInline" required>
                </div>
                <div class="col-auto">
                    <span id="passwordHelpInline" class="form-text">
                        Deve ter entre 6 a 20 caracteres.
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF &nbsp;</label>
                <input type="text" id="cpf" name="cpf" required />
            </div>
            <div class="mb-3">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" min="1900-01-01" max="2016-01-01" class="form-control" id="data_nascimento" name="data_nascimento" required>
            </div>
            <div class="mb-3 form-check">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                    <label class="form-check-label" for="invalidCheck">
                        Concordo que por motivos do site se encontrar em desenvolvimento, vou inserir somente dados ficticios aqui por enquanto.
                    </label>
                    <div class="invalid-feedback">
                        Você deve concordar antes de enviar.
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="submitButton">Enviar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js">
    </script>
    <script src="https://yandex.st/highlightjs/7.3/highlight.min.js"></script>
    <script src="\vendor\twbs\bootstrap\dist\js\bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script>
    $(document).ready(function() {
        $("#cpf").mask('000.000.000-00', {
            reverse: true
        });

        $("#submitButton").click(function(e) {

            var form = $("#registrarCliente")[0];


            if (form.checkValidity() === false) {
                form.classList.add('was-validated');
                return;
            }

            var formData = {
                nome: $("#nome").val(),
                email: $("#email").val(),
                senha: $("#senha").val(),
                cpf: $("#cpf").val(),
                data_nascimento: $("#data_nascimento").val()
            };

            $.ajax({
                type: "POST",
                url: "/clientes", // Sua rota POST /clientes
                data: formData,
                success: function(response) {
                    console.log(response);
                    alert("Cliente cadastrado com sucesso!");
                    window.location.href = "/clientes/listar?alert=successCreate";
                },
                error: function(xhr, status, error) {
                    alert("Erro ao cadastrar cliente. Detalhes: " + xhr.responseText);
                }
            });
        });
    });
    </script>
</body>