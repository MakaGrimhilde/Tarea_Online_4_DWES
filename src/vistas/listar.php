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
            <h2>Lista de usuarios</h2>
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
                        
                        <td>
                            
                        <?php

                            if($_COOKIE["usuario"] == "Mob12" or $_COOKIE["permisos"] == $re["id"]){

                        ?>

                            <a href="../vistas/index.php?accion=actualizar&id=<?= $re['id'] ?>">Editar</a>&nbsp;&nbsp;
                            <a href="../vistas/index.php?accion=eliminar&id=<?= $re['id'] ?>" 
                            onclick="return confirm('Está a punto de eliminar este usuario, ¿está seguro?')">Eliminar</a>&nbsp;&nbsp;


                        <?php

                            }
                        ?>

                        <a href="../vistas/index.php?accion=listarUsuario&id=<?=$re["id"]?>">Detalle</a>
                        </td>
                    </tr>

                    <?php } } ?>
                </table>
                <br/>
                <?php include '../includes/paginacion.php';?>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <a type="button" class="btn btn-primary" href="../vistas/index.php?accion=vistaPdf">Descargar PDF</a>&nbsp;
            <a type="button" class="btn btn-primary" href="../vistas/index.php?accion=exportarExcel">Descargar EXCEL</a>
        </div>
        <br>
        <div class="row justify-content-center">
            <form class="form-horizontal" action="../vistas/index.php?accion=importarExcel" method="POST" enctype="multipart/form-data">
                <input type="file" name="excel"/>
                <button type="submit" name="botonExcel" class="btn btn-primary">Importar Excel</button>
            </form>
        </div>
        <br>
    </body>
</html>