<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="/IPSPUPTM/assets/css/bootstrap.min.css">
     <!-- Estilos personalizados -->
    <link rel="stylesheet" href="/IPSPUPTM/assets/css/inicio.css"> <!-- Ruta personalizada -->

</head>
<body>
    <?php include 'C:/xampp/htdocs/IPSPUPTM/config/alertify.php' ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <div class="logo-container">
            <img src="/IPSPUPTM/recursos/img/IPSPUPTMlogo.png" alt="Logo IPSPUPTM" class="logo">
        </div>
        
                    <h2 class="mb-3">Recuperar Contraseña</h2>
                    <p class="mb-3">Por favor, ingresa tu nombre de usuario para iniciar el proceso de recuperación de contraseña.</p>
                    <form action="/IPSPUPTM/vistas/verificar_usuario.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Nombre de usuario</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar</button>
                        <div class="mt-3 text-center">
                            <a href="login.php">Volver al inicio de sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
     <script src= "/IPSPUPTM/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>