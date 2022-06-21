<!DOCTYPE html>
<html lang="es">
    <head>
        <?php require '../includes/head.php'; ?>
    </head>
    <body>
        <?php require '../includes/header.php'; ?>
        <br/><br/>
        <?php require '../includes/abrirsesion.php'; ?>
        <div class="row justify-content-center">
            <h2>Editar entrada</h2>
        </div>
        <br/>
        <script>
            CKEDITOR.replace( 'descripcion' );
        </script>
        <div class="row justify-content-center">
            <?php 
                foreach ($parametros["mensajes"] as $mensaje) : ?> 
                <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
            <?php endforeach; ?>
        </div><br/>
        <div class="row justify-content-center">
            <form action="index.php?accion=actualizarEntrada" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <label for="titulo">Título</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?= $parametros["datos"]["titulo"]?>"
                     required />  
                </div>
                <br/>
                <div class="row">
                    <label for="descripcion">Descripción</label>
                </div>
                <textarea class="ckeditor" id="descripcion" name="descripcion" required>
                    <?php echo $parametros["datos"]["descripcion"];?></textarea>
                <br/>
                <?php 
                    
                    if ($parametros["datos"]["imagen"] != null && $parametros["datos"]["imagen"] != ""){ ?>
                    <img src="../fotos/<?= $parametros["datos"]["imagen"] ?>" width="60"/></br>
                    <?php } 
                    
                ?>
                <br/>
                <div class="form-group">
                    <!--cuadro de tipo archivo para recoger la imagen que seleccione el usuario-->
                    <input type="file" class="form-control-file" id="imagen" name="imagen"/>
                </div>
                <br>
                <input type="hidden" name="id" value="<?php echo $id;?>">
                <!--botones para enviar los datos recogidos en el formulario y para limpiar los campos-->
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary" name="boton">Enviar</button>
                    <button type="reset" class="btn btn-primary">Limpiar</button>
                </div>
                <br/>
                <br/>
            </form>
        </div> 
    </body>
</html>