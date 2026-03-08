<?php
include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
?>

<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="labelPago" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelPago">Nuevo Pago de Cuota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/IPSPUPTM/app/pagos/modales/procesar_pago.php" method="POST">
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Seleccionar Contrato / Afiliado</label>
                        <select name="id_contrato" id="id_contrato_select" class="form-select" required
                            onchange="consultarSaldo(this.value)">
                            <option value="">Seleccione un contrato...</option>
                            <?php
                            $sql = "SELECT cp.ID_contrato, per.nombre, per.apellido, pl.nombre_plan 
                                    FROM contrato_plan cp 
                                    INNER JOIN persona per ON cp.ID_afiliado_contrato = per.cedula
                                    INNER JOIN planes pl ON cp.ID_planes_contrato = pl.ID_planes
                                    WHERE cp.estado_contrato = 'Activo'";
                            
                            $resultado = mysqli_query($conn, $sql); 
                            if ($resultado) {
                                while ($fila = mysqli_fetch_assoc($resultado)) {
                                    echo '<option value="' . $fila['ID_contrato'] . '">';
                                    echo $fila['nombre'] . ' ' . $fila['apellido'] . ' - ' . $fila['nombre_plan'];
                                    echo '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div id="info_saldo" class="alert alert-info d-none mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-info-circle"></i> Saldo Pendiente:</span>
                            <strong id="monto_pendiente">$ 0.00</strong>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Monto a Pagar ($)</label>
                            <input type="number" step="0.01" name="monto_cuota" class="form-control" required
                                placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Pago</label>
                            <input type="date" name="fecha_pago" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número de Cuota</label>
                            <input type="number" name="numero_cuota" id="input_cuota" class="form-control bg-light"
                                placeholder="Seleccione un contrato..." readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Método de Pago</label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Punto de Venta">Punto de Venta</option>
                                <option value="Pago Móvil">Pago Móvil</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function consultarSaldo(idContrato) {
    const contenedor = document.getElementById('info_saldo');
    const spanMonto = document.getElementById('monto_pendiente');
    const inputCuota = document.getElementById('input_cuota');
    const btnGuardar = document.querySelector('button[type="submit"]');

    if (!idContrato) {
        contenedor.classList.add('d-none');
        inputCuota.value = "";
        return;
    }

    let formData = new FormData();
    formData.append('id_contrato', idContrato);

    fetch('/IPSPUPTM/app/pagos/modales/obtener_saldo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Asignamos el saldo y la cuota desde el JSON
        spanMonto.innerText = '$ ' + data.saldo;
        inputCuota.value = data.proxima_cuota;
        contenedor.classList.remove('d-none');

        // Lógica de validación según el saldo calculado
        if (parseFloat(data.saldo) <= 0) {
            contenedor.className = "alert alert-success mb-3";
            spanMonto.innerText = "¡Plan Solventado!";
            inputCuota.value = ""; 
            btnGuardar.disabled = true; // Bloquea el botón si no debe nada
        } else {
            contenedor.className = "alert alert-warning mb-3";
            btnGuardar.disabled = false; // Habilita el botón si hay deuda
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>