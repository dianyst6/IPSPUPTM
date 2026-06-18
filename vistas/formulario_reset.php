<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4">
                <h2>Restablecer Contraseña</h2>
                <form action="/IPSPUPTM/vistas/actualizar_contrasena.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <div class="mb-3">
                        <label>Nueva Contraseña</label>
                        <input type="password" class="form-control" name="nueva_password" required>
                    </div>
                    <div class="mb-3">
                        <label>Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" name="confirmar_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Restablecer</button>
                </form>
            </div>
        </div>
    </div>
</div>