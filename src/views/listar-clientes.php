<?php
    require_once __DIR__ . "/../assets/header.html";
    //TODO: melhorar validação dos campos pelo jquery.
    global $objetos;
    $clientes = array();
    foreach( $objetos as $objeto){
        $clientes[] = $objeto->serializar();
    }
?>


<!DOCTYPE html>
<html lang="pt-BR">

<body>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        integrity="sha384-4LISF5TTJX/fLmGSxO53rV4miRxdg84mZsxmO8Rx5jGtp/LbrixFETvWa5a6sESd" crossorigin="anonymous">


    <div class="modal" tabindex="-1" id="editarCliente">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="salvarCliente">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" minlength="10" maxlength="280" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp" required>
                        </div>
                        <!-- TODO: incluir alteração da senha: <div class="mb-3">
                            <div class="col-auto">
                                <label for="senha" class="form-label">Senha</label>
                            </div>
                            <div class="col-auto">
                                <input type="password" id="senha" class="form-control" maxlength="20" minlength="6"
                                    aria-describedby="passwordHelpInline">
                            </div>
                            <div class="col-auto">
                                <span id="passwordHelpInline" class="form-text">
                                    Deve ter entre 6 a 20 caracteres. Deixe em branco para não alterar.
                                </span>
                            </div>
                        </div> -->
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF &nbsp;</label>
                            <input type="text" id="cpf" name="cpf" required />
                        </div>
                        <div class="mb-3">
                            <label for="data_nascimento">Data de Nascimento</label>
                            <input type="date" min="1900-01-01" max="2016-01-01" class="form-control"
                                id="data_nascimento" name="data_nascimento" required>
                        </div>
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="_METHOD" name="method" value="PUT">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="atualizarCliente"><i class="bi bi-save"></i>
                        Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <br>
                        <h2 class="m-0">Clientes</h2>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <?php if (isset($_GET['alert']) && $_GET['alert'] == "successCreate") : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible">
                            <h5><i class="icon bi bi-check"></i> Sucesso!</h5>
                            Cliente Cadastrado!
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (isset($_GET['alert']) && $_GET['alert'] == "successDelete") : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible">
                            <h5><i class="icon bi bi-check"></i> Sucesso! <button type="button" class="close"
                                    data-bs-dismiss="alert" aria-hidden="true">&times;</button></h5>
                            Cliente deletado!
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (isset($_GET['alert']) && $_GET['alert'] == "successEdit") : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible">
                            <h5><i class="icon bi bi-check"></i> Sucesso! <button type="button" class="close"
                                    data-bs-dismiss="alert" aria-hidden="true">&times;</button> </h5>
                            Cliente Atualizado!
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>NOME</th>
                                                <th> OPÇÕES <i class="bi bi-gear"> </i> </th>
                                                <th>DATA DE NASCIMENTO</th>
                                                <th>CPF</th>
                                                <th>EMAIL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($clientes as $cliente) : ?>
                                            <tr>
                                                <td><?= $cliente['nome']  ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-secondary" onclick="sendData(
                                                '<?= htmlspecialchars($cliente['id']) ?>',
                                                '<?= htmlspecialchars($cliente['nome']) ?>',
                                                '<?= htmlspecialchars($cliente['data_nascimento']) ?>',
                                                '<?= htmlspecialchars($cliente['cpf']) ?>',
                                                '<?= htmlspecialchars($cliente['email']) ?>')"> <i
                                                            class="bi bi-pencil"></i> Editar </button>
                                                    <button type="button" class="btn btn-dark"
                                                        onclick="deleteData( '<?= htmlspecialchars( $cliente['nome'] ) ?>' , '<?= $cliente['id'] ?>' )"
                                                        data-id-cliente="<?= $cliente['id'] ?>"> <i
                                                            class="bi bi-trash"></i> Apagar </button>
                                                </td>
                                                <td><?= $cliente['data_nascimento'] ?></td>
                                                <td><?= $cliente['cpf'] ?></td>
                                                <td><?= $cliente['email'] ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


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
    function sendData(id, nome, dataNascimento, cpf, email) {
        document.getElementById('id').value = id;
        document.getElementById('nome').value = nome;
        document.getElementById('cpf').value = cpf;
        document.getElementById('email').value = email;

        var partes = dataNascimento.split('/');
        var dataFormatadaParaModal = partes[2] + '-' + partes[1] + '-' + partes[0];

        document.getElementById('data_nascimento').value = dataFormatadaParaModal;


        $('#editarCliente').modal('show');
    }


    function deleteData(nomeCliente, idCliente) {
        if (!confirm("Deseja mesmo excluir " + nomeCliente + "? Essa ação é irreversível!")) {
            return;
        }
        $.ajax({
            type: "DELETE",
            url: "/clientes/" + idCliente,
            success: function(response) {
                console.log(response);
                window.location.href = "/clientes/listar?alert=successDelete";
            },
            error: function(xhr, status, error) {
                alert("Erro ao excluir cliente. Detalhes: " + xhr.responseText);
            }
        });
    }


    $(document).ready(function() {

        $("#cpf").mask('000.000.000-00', {
            reverse: true
        });

        $("#atualizarCliente").click(function(e) {

            var form = $("#salvarCliente")[0];
            console.log(form);


            if (form.checkValidity() === false) {
                form.classList.add('was-validated');
                return;
            }

            var idCliente = $("#id").val();

            var formData = {
                nome: $("#nome").val(),
                email: $("#email").val(),
                cpf: $("#cpf").val(),
                data_nascimento: $("#data_nascimento").val(),
                _METHOD: $("#_METHOD").val()
            };


            $.ajax({
                type: "POST",
                url: "/clientes/" + idCliente,
                data: formData,
                success: function(response) {
                    window.location.href = "/clientes/listar?alert=successEdit";
                },
                error: function(xhr, status, error) {
                    alert("Erro ao cadastrar cliente. Detalhes: " + xhr.responseText);
                }
            });
        });
    });
    </script>
</body>