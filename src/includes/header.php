<style type="text/css">
  #linkInicio {

    color:wheat;
}
</style>
<div class="row justify-content-center" id="cabecera">    
    <h1><img class="img" src="../img/logo.png">&nbsp;<a href="../vistas/inicio.php" id="linkInicio">Tarea Online 4</a></h1>
</div>
<ul class="nav">
  <li class="nav-item">
    <a class="nav-link" href="../vistas/index.php?accion=listarEntradas">Listar entradas</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../vistas/index.php?accion=insertarEntradas">Nueva entrada</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../vistas/index.php?accion=listar">Listar usuarios</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../vistas/index.php?accion=insertar">Nuevo usuario</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../vistas/index.php?accion=listarLogs">Logs</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../vistas/contacto.php">Contacto</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../includes/cerrarsesion.php">Cerrar sesión</a>
  </li>
</ul>
<div class="row justify-content-right" id="buscar">
    <form class="form-inline " action="../vistas/index.php?accion=buscar" method="GET">
      <input class="form-control mr-sm-2" type="text" placeholder="Buscar..." name="buscar">
      <button class="btn btn-primary my-2 my-sm-0" type="submit" name="buscar">Buscar</button>
    </form>
</div>