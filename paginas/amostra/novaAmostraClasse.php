<html>
    <head>
        <title>Kairos</title>
        <?php
          include "../include/include_css.php";
          include "../header/header.php";
          include "../../classes/model/validaOperario.php";
          require_once '../../classes/controller/ControllerAmostra.php';
        ?>
        <link rel="stylesheet" href="../../css/wickedpicker.css">
        <meta charset="UTF-8">
    </head>
    <body>
        <?php
          $ControllerAmostra = new ControllerAmostra;
          if(isset($_POST['cadastrar'])) {
              $endereco = new endereco();
              $cnpjNome = $endereco -> find($_POST['cnpj']);
              $cnpj = $cnpjNome -> cnpj;
              $departamento = $_POST['departamento'];
              $atividade = $_POST['atividade'];
              $_SESSION['departamento'] = $departamento;
              $_SESSION['atividade'] = $atividade;
              $_SESSION["i"] = 0;
              $_SESSION["jcount"] = 0;
              $amostra = new amostra;
              //guardando dados na sessoion
              $_SESSION['$cnpj'] = $_POST['cnpj'];
              $_SESSION['$departamento'] = $_POST['departamento'];
              $_SESSION['atividade'] = $_POST['atividade'];
              $count = $amostra -> findAllAmostras($atividade);

              if($count) {
                  echo "<script>alert('Já existe um indece para essa atividade por favor selecione outra atividade');"
                  ."window.location='./cadastroAmostra.php'</script>";
              }
          } else if(isset($_POST['cadastrarAmostra'])) {
              if($_POST['hora_inicial']>=$_POST['hora_final']) {
                  echo "<script>alert('Hora inicial não pode ser maior que hora final!');"
                  ."</script>";
                  $cnpj = $_POST['cnpj'];
                  $departamento = $_POST['departamento'];
                  $atividade = $_POST['atividade'];
                  $hora_inicial = $_POST['hora_inicial'];
                  $hora_final = $_POST['hora_final'];
                  $quantidade = $_POST['quantidade'];
              } else {
                  $cnpj = $_POST['cnpj'];
                  $departamento = $_POST['departamento'];
                  $atividade = $_POST['atividade'];
                  $hora_inicial = $_POST['hora_inicial'];
                  $hora_final = $_POST['hora_final'];
                  $quantidade = $_POST['quantidade'];

                  if($ControllerAmostra -> gravaAmostra($departamento, $atividade, $hora_inicial, $hora_final, $quantidade, $indice)) {

                  }
              }
          }
          if(isset($_POST['finalizar'])) {
              if($_SESSION["jcount"]>=2) {

                  $result = $ControllerAmostra -> verificaMedia();
                  $indice_final_media = $result/$_SESSION["jcount"];
                  $indice_final_media = $indice_final_media*60;
                  $ControllerAmostra -> insertAmostraDB();
                  //alert *****************/
                  $ControllerAmostra -> AlertMedia($indice_final_media);
                  $resultado = $indice_final_media*0.1;
                  //fim amostra
                  //cadastra uma meta inicial com o valor de 10%
                  $meta = new meta();
                  $meta -> setMediaIndice($indice_final_media);
                  $meta -> setQuantidade($_SESSION["jcount"]);
                  $meta -> setMeta(10);
                  $meta -> setResultado($resultado);
                  $acrescimo_meta = $resultado+$indice_final_media;
                  $meta -> setAcrescimoMeta($acrescimo_meta);
                  $departamento = $_SESSION['departamento'];
                  $atividade = $_SESSION['atividade'];
                  $meta -> setIdDeptoFK($departamento);
                  $meta -> setIdAtividadeFK($atividade);
                  $meta -> insertMeta();

                  $_SESSION["jcount"] = 0;
              } else {

                  $cnpj = $_SESSION['$cnpj'];
                  $departamento = $_SESSION['$departamento'];
                  $atividade = $_SESSION['atividade'];
                  echo "<script> alert('Para finalizar mais de 2 amostras devem ser cadastradas')</script>";
              }
          }
        ?>

        <div id="wrapper" >
            <div class="container-fluid">
                <div id="accordion">
                    <h3>Registro de Amostras</h3>
                    <div>
                        <form id="amostra" method="post" action="">
                            <div class="input-prepend">
                                <div class="alert alert-info alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> <strong>Ajuda:</strong> Para cadastrar a amostra o usuário deverá colocar a hora inicial da atividade, hora final da atividade, quantidade e clicar no botão cadastrar amostra <br>
                                    O minimo de amostras para se cadastrar são duas, para calcular o indice por minuto clique em finalizar.
                                </div>
                                <font size="4"><b>Quantidade de amostras:<span id="qtdAmostra"><?php echo $_SESSION["jcount"]; ?></span></b></font>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="cnpj">CNPJ</label>
                                        <input type="text" class="form-control" id="cnpj" name="cnpj" value="<?php echo $cnpj; ?>" placeholder="CNPJ" readonly="readonly">
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="departamento">Departamento</label>
                                        <input type="text" class="form-control" id="departamentoNone" name="departamentoNone" value="<?php
                                          $departamentoclass = new departamento();
                                          $nameDept = $departamentoclass -> find($departamento);
                                          echo $nameDept -> nome;
                                        ?>" placeholder="Departamento" readonly="readonly">
                                        <input style="display: none" type="text" class="form-control" id="departamento" name="departamento" value="<?php echo $departamento; ?>" placeholder="Departamento" readonly="readonly">

                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="atividade">Atividade</label>
                                        <input type="text" class="form-control" id="AtividadeNone" name="AtividadeNone" value="<?php
                                          $atividadeclass = new atividade();
                                          $name = $atividadeclass -> find($atividade);
                                          echo $name -> nome;
                                        ?>" placeholder="Atividade" readonly="readonly">
                                        <input style="display: none" type="text" class="form-control" id="atividade" name="atividade" value="<?php echo $atividade; ?>" placeholder="Departamento" readonly="readonly">

                                    </div>
                                </div>
                                <div class="row"><hr width=95%></div>
                                <div class="row" id="amostra_lista">
                                    <div class="form-group col-lg-4">
                                        <label for="cnpj">Hora Inicial</label>
                                        <input type="text" class="form-control" id="hora_inicial" onkeypress="javascript: mascara(this, hora);" maxlength="5" name="hora_inicial" placeholder="Hora Inicial" required>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="cnpj">Hora Final</label>
                                        <input type="text" class="form-control" id="hora_final"  onkeypress="javascript: mascara(this, hora);" maxlength="5" name="hora_final" placeholder="Hora Final" required>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="cnpj">Quantidade</label>
                                        <input type="text" class="form-control" id="quantidade" maxlength="5" onkeypress="javascript: mascara(this, soNumeros);" name="quantidade" placeholder="Quantidade" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-1"></div>
                                    <div class="form-group col-lg-3"></div>
                                    <div class="form-group col-lg-1">
                                        <input  id="cadastrarAmostra" type="submit" name="cadastrarAmostra" class="btn btn-success" value=" Cadastrar Amostra" id="teste">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form  id="amostra" method="post" action="">
                            <div class="form-group col-lg-1"></div>
                            <div class="form-group col-lg-3"></div>
                            <div class="form-group col-lg-1" >
                                <input  type="submit" name="finalizar"  class="btn btn-danger" value="Finalizar&nbsp;Amostra">
                            </div>
                        </form>
                    </div>
                    <h3>Amostras Cadastradas</h3>
                    <div>
                        <table id="tbl_Amostra" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Hora Inicial</th>
                                    <th>Hora Final</th>
                                    <th>Quantidade</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php include_once '../include/include_js.php'; ?>
    <script type="text/javascript" src="../../js/validadores.js"></script>
    <script type="text/javascript" src="../../js/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" src="../../js/wickedpicker.js"></script>
    <script type="text/javascript" src="../../js/validarHora.js"></script>
    <link href="../../css/jquery-ui.css" rel="stylesheet">
    <script src="../../js/datapicker/jquery-ui.js"></script>
    <script src="../../js/table.js"></script>
    <script>
                                            $("#accordion").accordion({
                                                collapsible: true
                                            });

    </script>
    <script>
    </script>
</html>

