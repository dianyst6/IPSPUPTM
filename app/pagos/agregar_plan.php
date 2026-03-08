
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="fa-solid fa-file-medical"></i> Crear Nuevo Plan de Salud</h4>
        </div>
        <form action="/IPSPUPTM/app/pagos/procesar_plan_completo.php" method="POST">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Plan</label>
                        <input type="text" name="nombre_plan" class="form-control" placeholder="Ej: Plan Platino 2026" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Precio ($)</label>
                        <input type="number" step="0.01" name="precio" class="form-control" required>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <hr>
                <h5>Exámenes</h5>
                
                <div id="contenedor-componentes">
                    <div class="row g-3 mb-2 componente-item">
                        <div class="col-md-7">
                            <select name="id_examen[]" class="form-select" required>
                                <option value="">Seleccione un examen...</option>
                                <?php
                                include 'config/database.php';
                                $ex = mysqli_query($conn, "SELECT ID_examen, nombre_examen FROM examenes");
                                while($f = mysqli_fetch_assoc($ex)) {
                                    echo "<option value='{$f['ID_examen']}'>{$f['nombre_examen']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="cantidad[]" class="form-control" placeholder="Cant. Máx" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-eliminar w-100" onclick="eliminarFila(this)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-success mt-3" onclick="agregarFila()">
                    <i class="fa-solid fa-plus"></i> Agregar Examen
                </button>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary px-5">Guardar Plan Completo</button>
            </div>
        </form>
    </div>
<script>
function agregarFila() {
    // Obtenemos la primera fila para clonarla
    const contenedor = document.getElementById('contenedor-componentes');
    const nuevaFila = document.querySelector('.componente-item').cloneNode(true);
    
    // Limpiamos los valores de la nueva fila
    nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
    nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    
    contenedor.appendChild(nuevaFila);
}

function eliminarFila(btn) {
    const filas = document.querySelectorAll('.componente-item');
    if (filas.length > 1) {
        btn.closest('.componente-item').remove();
    } else {
        alert("El plan debe tener al menos un componente.");
    }
}
</script>