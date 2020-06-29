      <nav class="navbar navbar-dark sticky-top navbar-expand-sm bg-dark py-0">
          <p id="username" class="text-white mt-3 ml-3"><?php echo $username; ?></p>
          <p id="userid" class="d-none"><?php echo $userid; ?></p>
          <p id="rol" class="d-none"><?php echo $rol; ?></p>
        <div class="collapse navbar-collapse justify-content-center" id="navbar-list">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo BASE_DIR; ?>/index.php">Inicio</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Usuarios
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/user/read_ud.php">Usuarios</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/user/stadistics.php">Rendimiento</a>
                <a id="usereg" class="dropdown-item" href="<?php echo BASE_DIR; ?>/user/registro.php">Usuario Nuevo</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/user/resetpassw.php">Reset Clave</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/user/login.php">Ingresar</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/user/logout.php">Salir</a>
              </div>
            </li> 
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Repuestos
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/partes/read.php">Ver</a>
                <a id="repcre" class="dropdown-item" href="<?php echo BASE_DIR; ?>/partes/create.php">Crear</a>
                <a id="repupd" class="dropdown-item" href="<?php echo BASE_DIR; ?>/partes/read_ud.php?accion=update">Modificar</a>
                <a id="repdel" class="dropdown-item" href="<?php echo BASE_DIR; ?>/partes/read_ud.php?accion=delete">Eliminar</a>
              </div>
            </li> 
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Cotizaciones</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/cotizacion/read_empresa.php">Ver Empresas</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/cotizacion/read_particular.php">Ver Particulares</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/cotizacion/create.php">Crear</a>
                <a id="cotupd" class="dropdown-item" href="<?php echo BASE_DIR; ?>/cotizacion/read_ud.php?accion=empresa">Actualizar de Empresas</a>
                <a id="cotdel" class="dropdown-item" href="<?php echo BASE_DIR; ?>/cotizacion/read_ud.php?accion=particular">Actualizar de Particulares</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink4" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Ventas</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/venta/read_empresa.php">Ver Empresas</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/venta/read_particular.php">Ver Particular</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/venta/create.php">Crear</a>
                <a id="venupd" class="dropdown-item" href="<?php echo BASE_DIR; ?>/venta/convert.php?tipo=empresa">Convertir Cotización</a>
                <a id="venupd" class="dropdown-item" href="<?php echo BASE_DIR; ?>/venta/read_ud.php?tipo=empresa">Actualizar de Empresas</a>
                <a id="vendel" class="dropdown-item" href="<?php echo BASE_DIR; ?>/venta/read_ud.php?tipo=particular">Actualizar de Particulares</a>
              </div>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink5" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Clientes
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/clientes/read_ud.php">Ver</a>
                <a class="dropdown-item" href="<?php echo BASE_DIR; ?>/clientes/create.php">Crear</a>
                <a id="cliupd" class="dropdown-item" href="<?php echo BASE_DIR; ?>/clientes/read_ud.php">Actualizar</a>
              </div>
            </li> 
          </ul>
        </div>
      </nav>