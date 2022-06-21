<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require '../includes/head.php';?>
    </head>
    <body>
        <?php require '../includes/header.php';?>
        <?php require '../includes/abrirsesion.php';?>
       
        <br/><br/>
        <div class="row justify-content-center">
            <h2>Lista de operaciones</h2>
        </div>
        <br/>
        <div class="row justify-content-center">
            <?php foreach ($parametros["mensajes"] as $mensaje) : ?> 
                <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
            <?php endforeach; ?>
        </div>
        <br/>
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <table class="table table-striped text-center">
                    <tr>
                        <th>ID</th>
                        <th>Usuario ID</th>
                        <th>Operación</th>
                        <th>Fecha de realización</th>
                        <th>Operaciones</th>
                    </tr>
                   
                    <?php foreach($parametros["datos"] as $re){ { ?> 

                    <tr>
                        <td><?=$re["id"]?></td>
                        <td><?=$re["usuario"]?></td>
                        <td><?=$re["operacion"]?></td>
                        <td><?=$re["fecha"]?></td>
                        <td>

                        <?php
                            if($_COOKIE["usuario"] == "Mob12"){
                        ?>
                        <a href="../vistas/index.php?accion=eliminarLog&id=<?= $re['id'] ?>" 
                        onclick="return confirm('Está a punto de eliminar esta operación, ¿está seguro?')">Eliminar</a>&nbsp;&nbsp;

                        <?php
                            }
                        ?>

                        <a href="../vistas/index.php?accion=listarLog&id=<?=$re["id"]?>">Detalle</a>
                        </td>
                    </tr>

                    <?php } } ?>
                </table>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <a type="button" class="btn btn-primary" href="../vistas/index.php?accion=vistaPdfLogs">Descargar PDF</a>
        </div>
        <br>
    </body>
</html>