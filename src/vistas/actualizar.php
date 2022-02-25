<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require 'includes/head.php';?>
    </head>
    <body>
        <?php require 'includes/header.php';?>
        <br/>
        <div class="row justify-content-center">
            <?php 
                foreach ($parametros["mensajes"] as $mensaje) : ?> 
                <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
            <?php endforeach; ?>
        </div>
        <div class="row justify-content-center">
            <!--Comienzo de la estructura del formulario. Los datos recogidos por el método POST serán recibidos en ejer_26.php-->    
            <form class="form-horizontal" method="POST" action="index.php?accion=actualizar" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm">
                        <!--cuadro de texto para recoger el nombre-->
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $parametros["datos"]["nombre"]?>"/>  
                    </div>
                    <div class="col-sm">
                        <!--cuadro de texto para recoger los apellidos-->
                        <label for="apellidos">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= $parametros["datos"]["apellidos"]?>"/>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm">
                        <!--cuadro de texto para recoger la dirección email-->
                        <label for="email">Email</label>
                        <input type="text" class="form-control" placeholder="Ej: elmotivao@gmail.com" id="email" name="email"
                        value="<?= $parametros["datos"]["email"]?>"/>
                    </div>
                </div>
                <br/>
                    <?php 
                    
                    if ($parametros["datos"]["imagen"] != null && $parametros["datos"]["imagen"] != ""){ ?>
                    <img src="fotos/<?= $parametros["datos"]["imagen"] ?>" width="60"/></br>
                    <?php } 
                    
                    ?>
                <br/>
                <div class="form-group">
                    <!--cuadro de tipo archivo para recoger la imagen que seleccione el usuario-->
                    <input type="file" class="form-control-file" id="imagen" name="imagen"/>
                </div>
                <br/>
                <input type="hidden" name="id" value="<?php echo $id;?>">
                <!--botones para enviar los datos recogidos en el formulario y para limpiar los campos-->
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary" name="boton">Actualizar</button>
                    <button type="reset" class="btn btn-primary">Limpiar</button>
                </div>
            </form> <!--Fin del formulario-->
        </div>
    </body>
</html>