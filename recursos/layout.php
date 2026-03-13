<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control IPSPUPTM</title>

    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css">

    <!-- Font Awesome local -->
    <link rel="stylesheet" href="/IPSPUPTM/assets/fontawesome/css/all.min.css"> <!-- Ruta local -->

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/IPSPUPTM/assets/css/style.css"> <!-- Ruta personalizada -->


</head>

<body>

    <?php include 'C:/xampp/htdocs/IPSPUPTM/recursos/header.php' ?>
    <?php include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php' ?>
    <!-- Agrega este botón en tu HTML -->


    <div class="">
        <aside id="custom-sidebar">

            <ul class="custom-sidebar-nav">
                <?php
                $role_id = $_SESSION['role_id'];
                if ($role_id == 1) { // Administrador
                ?>
                <li><a href="/IPSPUPTM/home.php?vista=inicial"><i class="fas fa-home"></i> Inicio</a></li>
                <li>
                    <a href="#submenuPacientes" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-user-plus"></i> Gestión <br>de Pacientes
                    </a>
                    <ul class="collapse list-unstyled " id="submenuPacientes">
                        <li><a href="/IPSPUPTM/home.php?vista=afiliados">Afiliados</a></li>
                        <li><a href="/IPSPUPTM/home.php?vista=beneficiarios">Beneficiarios</a></li>
                        <li><a href="/IPSPUPTM/home.php?vista=comunidaduptm">Comunidad UPTM</a></li>
                    </ul>
                </li>

                <li><a href="/IPSPUPTM/home.php?vista=citas"><i class="fas fa-calendar-plus"></i>Gestión de Citas</a>
                </li>
                 <li>
                    <a href="#submenuPagos" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                       <i class="fa-solid fa-money-check-dollar"></i> Administracion <br>de Pagos
                    </a>
                    <ul class="collapse list-unstyled " id="submenuPagos">
                        <li><a href="/IPSPUPTM/home.php?vista=principalpagos">Gestionar pagos</a></li>
                        <li><a href="/IPSPUPTM/home.php?vista=gestionplanes">Gestionar Planes salud</a></li>
                      
                    </ul>
                </li>
                <li><a href="/IPSPUPTM/home.php?vista=historiasmedicas"><i class="fas fa-book-medical"></i>Historias Médicas</a></li>
                <li><a href="/IPSPUPTM/home.php?vista=reportes"><i class="fas fa-chart-bar"></i>Gestión de Reportes</a>
                </li>
                
                <li><a href="/IPSPUPTM/home.php?vista=configuracion"><i class="fas fa-cog"></i> Configuración</a></li>
                <?php } ?>

                <?php
                $role_id = $_SESSION['role_id'];
                if ($role_id == 2) { // Secretaria
                ?>
                <li><a href="/IPSPUPTM/home.php?vista=inicial"><i class="fas fa-home"></i> Inicio</a></li>
                <li>
                    <a href="#submenuPacientes" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fas fa-user-plus"></i> Gestión <br>de Pacientes
                    </a>
                    <ul class="collapse list-unstyled " id="submenuPacientes">
                        <li><a href="/IPSPUPTM/home.php?vista=afiliados">Afiliados</a></li>
                        <li><a href="/IPSPUPTM/home.php?vista=beneficiarios">Beneficiarios</a></li>
                        <li><a href="/IPSPUPTM/home.php?vista=comunidaduptm">Comunidad UPTM</a></li>
                    </ul>
                </li>

                <li><a href="/IPSPUPTM/home.php?vista=citas"><i class="fas fa-calendar-plus"></i>Gestión de Citas</a>
                </li>
                 <li>
                    <a href="#submenuPagos" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                       <i class="fa-solid fa-money-check-dollar"></i> Administracion <br>de Pagos
                    </a>
                    <ul class="collapse list-unstyled " id="submenuPagos">
                        <li><a href="/IPSPUPTM/home.php?vista=principalpagos">Gestionar pagos</a></li>
                        <li><a href="/IPSPUPTM/home.php?vista=gestionplanes">Gestionar Planes salud</a></li>
                      
                    </ul>
                </li>

                <li><a href="/IPSPUPTM/home.php?vista=reportes"><i class="fas fa-chart-bar"></i>Gestión de Reportes</a>
                </li>
                <?php } ?>

                <?php
                $role_id = $_SESSION['role_id'];
                if ($role_id == 3) { // Medico
                ?>

                <li><a href="/IPSPUPTM/home.php?vista=historiasmedicas"><i class="fas fa-book-medical"></i>Historias Médicas</a></li>
                <?php } ?>

                <li><a href="/IPSPUPTM/home.php?vista=ayuda"><i class="fas fa-question-circle"></i> Ayuda</a></li>
            </ul>
        </aside>
        <div class="cont-general mt-1 pt-1" style="width: 100%; flex-grow: 1;">
            <div class="main p-3">
                    <div>

                        <?php
                    // Incluir el contenido específico del módulo
                    if (isset($contenido)) {
                        include $contenido;
                    } else {
                        echo "<p>Contenido no disponible.</p>";
                    }
                    ?>
                    </div>
                
            </div>
        </div>
    </div>

    <?php include 'C:/xampp/htdocs/IPSPUPTM/recursos/alertas/eliminadomodal.php' ?>


    <!-- Bootstrap JS desde CDN -->
    <script src="/IPSPUPTM/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

    <!-- Script personalizado -->
    <script src="/IPSPUPTM/assets/js/script.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
    document.getElementById('customSidebarToggle').onclick = function() {
        document.getElementById('custom-sidebar').classList.toggle('active');
    };
    </script>

</body>
<footer class=" bg-dark text-white footerm">
    <div class="container text-center">
        <p>&copy; <?php echo date("Y"); ?> IPSPUPTM. Todos los derechos reservados.</p>
        <p>Diseñado Gabriela, Gregory, Dianys, Alondra.</p>
    </div>
</footer>