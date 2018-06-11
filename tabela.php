<?php
session_start();
if ($_SESSION['auth'] != 1) {
    header('location: index.php');
}

require 'vendor/autoload.php';

if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {        
        case 'cabecalho':
            echo'<script>alert("Cabeçalho segue ordem incorreta, ou palavras estão em minúsculo")</script>';
            break;
        case 'aprovado':
            echo'<script>alert("Atualização dos dados realizada")</script>';
            break;
    }
}

$db = new MysqliDb(Array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db' => 'mundowap')
);

$produtos = $db->get('produtos');
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/dataTable/main.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
        <link rel="stylesheet" href="css/style.css">
        <title>Tela de Produtos</title>
    </head>
    <body>
        <div class="container">

            <div class="row">
                <div class="col">
                    <form action="includes/upload.php" method="post" enctype="multipart/form-data" id="formExcel">
                        <input type="file" id="fileUpload" name="fileUpload">
                        <input type="submit" value="Enviar" />
                    </form>                   
                </div>
                <div class="col text-right">
                    <input type="button" name='logout' id='logout' value="Logout" class="btn btn-primary">
                </div>
            </div>
            <div class="row">

                <table id="table_id" class="cell-border">
                    <thead>
                        <tr>
                            <th>EAN</th>
                            <th>Nome do Produto</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Data de Fabricação</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto) { ?>
                            <tr>

                                <td><?php echo $produto['ean'] ?></td>
                                <td><?php echo $produto['nome'] ?></td>
                                <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.') ?></td>
                                <td><?php echo $produto['estoque'] ?></td>
                                <td><?php if ($produto['data_fabricacao'] != null) {
                                echo date('d/m/Y', strtotime($produto['data_fabricacao']));
                            } ?></td>
                                <td><a href='includes/remove_produto.php?ean=<?php echo $produto['ean'] ?>'>Remover</a></td>

                            </tr> 
<?php } ?>
                    </tbody>
                </table>

            </div> 
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
        <script src="js/dataTable/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/dataTable/vendor/jquery-1.11.2.min.js"><\/script>')</script>
        <script type="text/javascript"  src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
        <script type="text/javascript"  src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="//malsup.github.io/jquery.form.js"></script>
        <script>

            $(document).ready(function () {

                $('#table_id').DataTable({
                    "autoWidth": true,
                    "ordering": false,
                    "paging": false,
                    "searching": false,
                    "lengthChange": false,
                    "info": false
                });

                $('#logout').click(function () {
                    window.location.href = 'includes/logout.php';
                });


            });

        </script>

    </body>
</html>