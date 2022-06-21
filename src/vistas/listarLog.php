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
            <h2>Detalle</h2>
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

                        <?php
                            if($_COOKIE["usuario"] == "Mob12"){
                        ?>

                        <th>Operaciones</th>

                        <?php
                            }
                        ?>
                    </tr>
                    
                    <tr>
                        <td><?=$parametros["datos"]["id"]?></td>
                        <td><?=$parametros["datos"]["usuario"]?></td>
                        <td><?=$parametros["datos"]["operacion"]?></td>
                        <td><?=$parametros["datos"]["fecha"]?></td>
                        <td>

                        <?php
                            if($_COOKIE["usuario"] == "Mob12"){
                        ?>

                        <a href="../vistas/index.php?accion=eliminarLog&id=<?= $parametros["datos"]['id'] ?>" 
                        onclick="return confirm('Está a punto de eliminar esta operación, ¿está seguro?')">Eliminar</a>&nbsp;&nbsp;

                        <?php
                            }
                        ?>

                        </td>
                    </tr>
                </table>
            </div>
        </div>