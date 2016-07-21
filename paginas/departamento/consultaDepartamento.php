
<html>
    <head>
        <meta charset="UTF-8">
        <title>Kairos</title>
        <?php
          include "../include/include_css.php";
          include "../header/header.php";
          include "../../classes/model/validaOperario.php";
          include '../../classes/table/tableDept.php';
          include "../../classes/model/validaLider.php";
        ?>
    </head>
    <body>
        <div id="page-wrapper" style="overflow-x: hidden; padding-left: 250px; height:100%; padding-top: 30px;">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Alterar Departamento</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <table id="tbl_Dept" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Nome</td>
                                            <td>Endere&ccedil;o</td>
                                            <td >CNPJ</td>
                                            <td width="15%">Lider</td>
                                            <td width="15%">Gerente</td>
                                            <td>A&ccedil;&otilde;es</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php include_once '../include/include_js.php'; ?>
</html>
