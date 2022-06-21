<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require '../includes/head.php';?>
    </head>
    <body>
        <?php require '../includes/header.php';?>
        <br/><br/>
        <?php require '../includes/abrirsesion.php'; ?>
        <div class="row justify-content-center">
            <h2>Nuevo Usuario</h2>
        </div>
        <br/><br/>
        <div class="row justify-content-center">
            <?php foreach ($parametros["mensajes"] as $mensaje) : ?> 
                <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
            <?php endforeach; ?>
        </div>
        <br/>
        <div class="row justify-content-center">
                    <!--Comienzo de la estructura del formulario-->    
                    <form class="form-horizontal" method="POST" action="../vistas/index.php?accion=insertar" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm">
                                
                                <label for="nick">Usuario</label>
                                <input type="text" class="form-control" id="nick" name="nick"
                                    required value="<?= $parametros["datos"]["nick"] ?>"/>  
                            </div>
                            <div class="col-sm">
                               
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    required value="<?= $parametros["datos"]["password"] ?>"/>  
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-sm">
                                
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    required value="<?= $parametros["datos"]["nombre"] ?>"
                                />  
                            </div>
                            <div class="col-sm">
                               
                                <label for="apellidos">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                    required value="<?= $parametros["datos"]["apellidos"] ?>"  
                                />  
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-sm">
                                
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    required value="<?= $parametros["datos"]["email"] ?>"
                                />  
                            </div>
                            <div class="col-sm">
                                
                                <label for="imagen">Imagen</label>
                                <input type="file" class="form-control-file" id="imagen" name="imagen"
                                    required value="<?= $parametros["datos"]["imagen"] ?>"  
                                />  
                            </div>
                        </div>
                        <br/>
                        
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