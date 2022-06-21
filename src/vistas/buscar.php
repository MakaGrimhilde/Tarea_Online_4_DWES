<!DOCTYPE html>
<html lang="es">
<head>
    <?php require '../includes/head.php';?>
</head>
<body>
    <?php require '../includes/header.php';?>
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
                    <!--bucle foreach que recorre toda la tabla y recoge los elementos que se encuentren en nick, nombre, 
                    apellidos, email e imagen-->
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
                        <td><a href="../vistas/index.php?accion=actualizar&id=<?= $re['id'] ?>">Editar</a>&nbsp;&nbsp;
                        <a href="../vistas/index.php?accion=eliminar&id=<?= $re['id'] ?>">Eliminar</a>&nbsp;&nbsp;
                        <a href="../vistas/index.php?accion=listarUsuario&id=<?=$re["id"]?>">Detalle</a>
                        </td>
                    </tr>

                    <?php } } ?>
                </table>
                <br/>
            </div>
        </div>
</body>
</html>