<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require 'includes/head.php';?>
    </head>
    <body>
        <?php require 'includes/header.php';?>
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
                        <th>Nick</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Imagen</th>
                        <th>Operaciones</th>
                    </tr>
                    <!--bucle foreach que recorre toda la tabla y recoge los elementos que se encuentren en nombre, 
                    apellidos, email e imagen-->
                    <?php foreach($parametros["datos"] as $re){ { ?> 

                    <tr>
                        <td><?=$re["nick"]?></td>
                        <td><?=$re["nombre"]?></td>
                        <td><?=$re["apellidos"]?></td>
                        <td><?=$re["email"]?></td>
                        <td>
                            <?php
                                if ($re["imagen-avatar"] != null){

                                    echo '<img src="fotos/'.$re["imagen-avatar"].'" width="40"/>'.$re["imagen-avatar"];

                                }
                            ?>
                        </td>
                        <td><a href="index.php?accion=actualizar&id=<?= $re['id'] ?>">Editar</a>&nbsp;&nbsp;
                        <a href="index.php?accion=eliminar&id=<?= $re['id'] ?>">Eliminar</a>&nbsp;&nbsp;
                        <!--<a href="./tabladetalle.php?id=<?=$re["id"]?>">Detalle</a>-->
                        </td>
                    </tr>

                    <?php } } ?>
                </table>
            </div>
        </div>
    </body>
</html>