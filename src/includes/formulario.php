<?php require 'includes/recibir.php';?>
<div class="row justify-content-center">
    <h2>Login de usuario</h2>
</div>
<br/>
<div class="row justify-content-center">
            <!--Comienzo de la estructura del formulario-->    
            <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm">
                        <!--cuadro de texto para recoger el nombre-->
                        <label for="usuario">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario"
                            
                            <?php
                                //muestra el nombre de usuario ya guardado en la cookie
                                if(isset($_COOKIE["usuario"])){
                                    echo "value='{$_COOKIE["usuario"]}'";
                                }

                            ?>
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