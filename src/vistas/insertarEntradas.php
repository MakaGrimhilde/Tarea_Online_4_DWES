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
        <h2>Nueva Entrada</h2>
    </div>
    <br/>
    <div class="row justify-content-center">
        <?php foreach ($parametros["mensajes"] as $mensaje) : ?> 
            <div class="alert alert-<?= $mensaje["tipo"] ?>"><?= $mensaje["mensaje"] ?></div>
        <?php endforeach; ?>
    </div>
    <br/>
    <script>
        CKEDITOR.replace( 'descripcion' );
    </script>
    <div class="row justify-content-center">
        <form action="../vistas/index.php?accion=insertarEntradas" method="POST" enctype="multipart/form-data">
            <div class="row">
                <label for="titulo">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required />  
            </div>
            <br/>
            <div class="row">
                <label for="descripcion">Descripción</label>
            </div>
            <textarea class="ckeditor" id="descripcion" name="descripcion" required></textarea>
            <br/>
            <div class="row">
                <label for="imagen">Imagen</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen" required />  
            </div>
            <br/>
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