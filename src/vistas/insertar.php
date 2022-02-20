<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require 'includes/head.php';?>
    </head>
    <body>
        <?php require 'includes/header.php';?>
        <br/>
        <div class="row justify-content-center">
            <h2>Nuevo Usuario</h2>
        </div>
        <br/>
        <div class="row justify-content-center">
            <?php foreach ($parametros["mensajes"] as $mensaje) : ?> 
            <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
            <?php endforeach; ?>
        </div>
        <br/>
        <div class="row justify-content-center">
                    <!--Comienzo de la estructura del formulario-->    
                    <form class="form-horizontal" method="POST" action="index.php?accion=insertar" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm">
                                <!--cuadro de texto para recoger el nombre-->
                                <label for="nick">Usuario</label>
                                <input type="text" class="form-control" id="nick" name="nick"
                                    
                                    <?php
                                        //muestra el nombre de usuario ya guardado en la cookie
                                        if(isset($_COOKIE["usuario"])){
                                            echo "value='{$_COOKIE["usuario"]}'";
                                        }

                                    ?>
                                    required value="<?= $parametros["datos"]["nick"] ?>"
                                />  
                            </div>
                            <div class="col-sm">
                                <!--cuadro de texto para recoger la contraseña-->
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password"

                                    <?php
                                        //muestra la contraseña ya guardada en la cookie
                                        if(isset($_COOKIE["password"])){
                                            echo "value='{$_COOKIE["password"]}'";
                                        }
                                    ?>
                                    required value="<?= $parametros["datos"]["password"] ?>"  
                                />  
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-sm">
                                <!--cuadro de texto para recoger el nombre-->
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    required value="<?= $parametros["datos"]["nombre"] ?>"
                                />  
                            </div>
                            <div class="col-sm">
                                <!--cuadro de texto para recoger la contraseña-->
                                <label for="apellidos">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    required value="<?= $parametros["datos"]["apellidos"] ?>"  
                                />  
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-sm">
                                <!--cuadro de texto para recoger el nombre-->
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    required value="<?= $parametros["datos"]["email"] ?>"
                                />  
                            </div>
                            <div class="col-sm">
                                <!--cuadro de texto para recoger la contraseña-->
                                <label for="imagen">Imagen</label>
                                <input type="file" class="form-control-file" id="imagen" name="imagen"
                                    required value="<?= $parametros["datos"]["imagen"] ?>"  
                                />  
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <!--Casillas de verificación para recordar usuario y mantener sesión abierta-->
                            <div class="col-sm">
                                <label>
                                    <input type="checkbox" name="recuerdame">
                                        Recordar usuario
                                </label>
                                &nbsp;&nbsp;
                                <label>
                                    <input type="checkbox" name="mantener">
                                        Mantener sesión
                                </label>
                            </div>
                        </div>

                        <?php
                            if(isset($_GET['error'])){ //si existen errores en el formulario

                                if ($_GET['error'] == "dato"){ //si es un dato incorrecto

                                    echo '<div class="alert alert-danger row justify-content-center" style="margin-top:5px;">'. 
                                    "El usuario y/o contraseña son incorrectos, inténtelo de nuevo<br/>".'</div>';

                                } elseif ($_GET['error'] == "fuera"){ //si se intenta acceder directamente a la página sin login

                                    echo '<div class="alert alert-danger row justify-content-center" style="margin-top:5px;">'. 
                                    "Debe logearse antes para poder acceder a esta página<br/>".'</div>';          
                                }
                            }     
                        ?>
                        
                        <br/>
                        <!--botones para enviar los datos recogidos en el formulario y para limpiar los campos-->
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-primary" name="boton">Enviar</button>
                            <button type="reset" class="btn btn-primary">Limpiar</button>
                        </div>
                    </form> <!--Fin del formulario-->
        </div>
    </body>
</html>