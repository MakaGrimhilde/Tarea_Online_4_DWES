<?php 
ob_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta	name="viewport"	content="width=device-width,	initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="row1 justify-content-center" style="text-align:center">    
            <h1>Tarea Online 4</h1>
        </div>
        
        <!--Html para mostrar la tabla que recoge los registros de usuarios en el PDF-->
        <br/><br/>
            <div class="row justify-content-center">
                <div class="col-sm-8">
                    <table class="table table-striped text-center" style="text-center">
                        <tr>
                            <th>Nick</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Imagen</th>
                        </tr>
                        <?php foreach($parametros["datos"] as $re){ { ?> 

                            <tr>
                                <td><?=$re["nick"]?></td>
                                <td><?=$re["nombre"]?></td>
                                <td><?=$re["apellidos"]?></td>
                                <td><?=$re["email"]?></td>
                                <td>
                                    <?php
                                        if ($re["imagen"] != null){

                                            echo '<img src="../fotos/'.$re["imagen"].'" width="50"/>';

                                        }
                                    ?>
                                </td>

                            </tr>

                        <?php } } ?>
                    </table>
                </div>
            </div>
    </body>
</html>

<!--hoja de estilos interna para la tabla que se mostrará en el PDF-->
<style type="text/css">

    .row1 {

        background-color: orange;
        height: 8%;
        color: wheat;
        margin: 0;
    }

    .row { 

        margin: auto;
    
    }

    .table {

        justify-content: center;
        align: center;
        text-align: center;
        padding-left: 60px;
    }

    th, td {

        padding: 10px;
    }

    th {

        background-color: #f2f2f2;
    }

</style>
<?php
$html = ob_get_clean();

require '../pdf/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('P', 'A4', 'es', 'true', 'UTF-8');
$html2pdf->writeHTML($html);
$html2pdf->output('listado_usuarios.pdf');

?>