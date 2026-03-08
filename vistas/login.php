<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/IPSPUPTM/assets/css/inicio.css"> <title>Inicia sesión</title>
    
</head>

<body class="bg-dark d-flex justify-content-center align-items-center rounded shadow-lg" style="min-height: 100vh;">
    <div class=" bd-primary card shadow p-4" style="max-width: 400px; width: 100%;">
        <div class="logo-container">
            <img src="/IPSPUPTM/recursos/img/logoipspsazul.png" alt="Logo IPSPUPTM" class="logo">
        </div>
        <h6 class=" text-black text-center mb-4"><b>Inicia sesión</b></h6>
        <?php include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php' ?>
        <?php
        session_start();
        if (isset($_SESSION['login_error'])) {
            echo '<div class="alert alert-danger" role="alert">';
            echo $_SESSION['login_error'];
            echo '</div>';
            unset($_SESSION['login_error']);
        }
        if (isset($_GET['contrasena_restablecida']) && $_GET['contrasena_restablecida'] == 1) {
             echo "<script>
                    alertify.message('Contraseña restablecida exitosamente. Por favor, inicie sesión.');
                  </script>";
        }
        ?>
        <form action="/IPSPUPTM/Inicio/iniciosesion.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label text-secondary"><h6>Usuario</h6></label>
                <input type="text" class="form-control" id="username" name="username" required placeholder="Ingrese su Usuario" >
            </div>
            <div class="mb-3">
                <label for="password" class="form-label text-secondary"><h6>Contraseña</h6></label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Ingrese su contraseña">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="/IPSPUPTM/vistas/recuperar_contraseña.php" class="text-decoration-none">Recuperar contraseña</a>
            </div>
            <button type="submit" class="btn btn-dark w-100" style="background-color: #001f3f;">Ingresar</button>
        </form>
    </div>
     <script src= "/IPSPUPTM/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
