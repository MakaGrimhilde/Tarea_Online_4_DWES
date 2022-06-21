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
            <h2>Enviar un correo</h2>
        </div>
        <br/>
        <div class="row justify-content-center">
            <form class="form-horizontal" action="../vistas/index.php?accion=enviarEmail" method="POST">
                <div class="row">
                    <div class="col-sm">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="col-sm">
                        <label for="email">Correo electrónico</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>
                <br>
                <div class="row">
                    <label for="mensaje">Mensaje</label>
                    <textarea name="mensaje" class="form-control" cols="55" rows="8" required></textarea>
                </div>
                <br>
                <div class="row">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div> 
            </form>
        </div>