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
            <h2>Lista de entradas</h2>
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
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Categoría</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Operaciones</th>
                    </tr>
                    <!--bucle foreach que recorre toda la tabla y recoge los elementos que se encuentren en nick, nombre, 
                    apellidos, email e imagen-->
                    <?php foreach($parametros["datos"] as $re){ { ?> 

                    <tr>
                        <td><?=$re["titulo"]?></td>
                        <td><?=$re["usuario_id"]?></td> 
                        <td><?=$re["categoria_id"]?></td>              
                        <td>
                            <?php
                                if ($re["imagen"] != null){

                                    echo '<img src="../fotos/'.$re["imagen"].'" width="50"/>';

                                }
                            ?>
                        </td>
                        <td><?=$re["descripcion"]?></td>
                        <td><?=$re["fecha"]?></td>
                        <td>

                        <?php
                            
                            if($_COOKIE["usuario"] == "Mob12" or $_COOKIE["permisos"] == $re["usuario_id"]){

                        ?>
                        
                        <a href="../vistas/index.php?accion=actualizarEntrada&id=<?= $re['id'] ?>">Editar</a>&nbsp;&nbsp;
                        <a href="../vistas/index.php?accion=eliminarEntrada&id=<?= $re['id'] ?>"
                        onclick="return confirm('Está a punto de eliminar esta entrada, ¿está seguro?')">Eliminar</a>&nbsp;&nbsp;
                        
                        <?php

                            }    

                        ?>
                        
                        <a href="../vistas/index.php?accion=listarEntrada&id=<?=$re["id"]?>">Detalle</a>
                        </td>
                    </tr>

                    <?php } } ?>
                </table>
                <br/>
                <?php include '../includes/paginacionEntradas.php';?>
            </div>
        </div>
    </body>
</html>