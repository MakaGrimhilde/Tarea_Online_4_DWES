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
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Fecha</th>
                        <?php
                            if($_COOKIE["usuario"] == "Mob12" or $_COOKIE["permisos"] == $parametros["datos"]["usuario_id"]){
                        ?>
                        <th>Operaciones</th>

                        <?php
                            }
                        ?>
                    </tr>
                    
                    <tr>
                        <td><?=$parametros["datos"]["titulo"]?></td>
                        <td><?=$parametros["datos"]["descripcion"]?></td>

                        <td>
                            <?php
                                if ($parametros["datos"]["imagen"] != null){

                                    echo '<img src="../fotos/'.$parametros["datos"]["imagen"].'" width="150"/>';

                                }
                            ?>
                        </td>
                        <td><?=$parametros["datos"]["fecha"]?></td>

                        <?php

                            if($_COOKIE["usuario"] == "Mob12" or $_COOKIE["permisos"] == $parametros["datos"]["usuario_id"]){


                        ?>

                            <td><a href="../vistas/index.php?accion=actualizarEntrada&id=<?= $parametros["datos"]['id'] ?>">Editar</a>&nbsp;&nbsp;
                            <a href="../vistas/index.php?accion=eliminarEntrada&id=<?= $parametros["datos"]['id'] ?>" 
                            onclick="return confirm('Está a punto de eliminar esta entrada, ¿está seguro?')">Eliminar</a>&nbsp;&nbsp;
                            </td>

                        <?php

                            }
                        
                        ?>

                    </tr>
                </table>
            </div>
        </div>