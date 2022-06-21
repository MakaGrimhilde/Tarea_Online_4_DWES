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
                        <th>Nick</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Imagen</th>

                        <?php
                            if($_COOKIE["usuario"] == "Mob12" or $_COOKIE["permisos"] == $parametros["datos"]["id"]){
                        ?>
                        <th>Operaciones</th>

                        <?php
                            }
                        ?>
                    </tr>
                    
                    <tr>
                        <td><?=$parametros["datos"]["nick"]?></td>
                        <td><?=$parametros["datos"]["nombre"]?></td>
                        <td><?=$parametros["datos"]["apellidos"]?></td>
                        <td><?=$parametros["datos"]["email"]?></td>
                        <td>
                            <?php
                                if ($parametros["datos"]["imagen"] != null){

                                    echo '<img src="../fotos/'.$parametros["datos"]["imagen"].'" width="150"/>';

                                }
                            ?>
                        </td>

                        <td>

                        <?php

                            if($_COOKIE["usuario"] == "Mob12" or $_COOKIE["permisos"] == $parametros["datos"]["id"]){

                        ?>

                        <a href="../vistas/index.php?accion=actualizar&id=<?= $parametros["datos"]['id'] ?>">Editar</a>&nbsp;&nbsp;
                        <a href="../vistas/index.php?accion=eliminar&id=<?= $parametros["datos"]['id'] ?>" 
                        onclick="return confirm('Está a punto de eliminar este usuario, ¿está seguro?')">Eliminar</a>&nbsp;&nbsp;
                        </td>

                        <?php

                            }

                        ?>

                    </tr>
                </table>
            </div>
        </div>