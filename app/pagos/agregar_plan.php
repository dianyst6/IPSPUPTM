
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
                        <label class="form-label">Precio Plan ($)</label>
                        <input type="number" step="0.01" name="precio" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cobertura Póliza ($)</label>
                        <input type="number" step="0.01" name="monto_cobertura" class="form-control" placeholder="Ej: 500.00" required>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <!-- SECCIÓN EXÁMENES INDIVIDUALES -->
                    <div class="col-md-6 border-end">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fa-solid fa-microscope text-primary"></i> Límites por Examen</h5>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalNuevoExamen">
                                <i class="fa-solid fa-plus"></i> Nuevo Examen
                            </button>
                        </div>
                        <div id="contenedor-examenes">
                            <!-- Fila de ejemplo (Template) -->
                            <div class="row g-2 mb-2 examen-item p-2 border rounded bg-white shadow-sm">
                                <div class="col-md-8">
                                    <select name="id_examen[]" class="form-select select-examen">
                                        <option value="">Seleccione un examen...</option>
                                        <?php
                                        include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';
                                        $ex = mysqli_query($conn, "SELECT ID_examen, nombre_examen FROM examenes ORDER BY nombre_examen ASC");
                                        while($f = mysqli_fetch_assoc($ex)) {
                                            echo "<option value='{$f['ID_examen']}'>{$f['nombre_examen']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="cantidad_examen[]" class="form-control" placeholder="Cant." title="Cantidad Máxima">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 h-100" onclick="eliminarFila(this, '.examen-item')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-link btn-sm text-decoration-none" onclick="agregarFilaExamen()">
                            <i class="fa-solid fa-circle-plus"></i> Añadir otro examen específico
                        </button>
                    </div>

                    <!-- SECCIÓN CATEGORÍAS (LÍMITES GLOBALES) -->
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fa-solid fa-tags text-success"></i> Límites por Categoría (Global)</h5>
                            <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">
                                <i class="fa-solid fa-plus"></i> Nueva Categoría
                            </button>
                        </div>
                        <div id="contenedor-categorias">
                            <!-- Fila de ejemplo (Template) -->
                            <div class="row g-2 mb-2 categoria-item p-2 border rounded bg-white shadow-sm">
                                <div class="col-md-6">
                                    <select name="id_categoria_comp[]" class="form-select select-categoria">
                                        <option value="">Seleccione una categoría...</option>
                                        <?php
                                        $cat = mysqli_query($conn, "SELECT id_categoria, nombre_categoria FROM categorias_examenes ORDER BY nombre_categoria ASC");
                                        while($c = mysqli_fetch_assoc($cat)) {
                                            echo "<option value='{$c['id_categoria']}'>{$c['nombre_categoria']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="cantidad_categoria[]" class="form-control" placeholder="Cant." title="Cantidad Máxima Global">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" step="0.01" name="monto_categoria[]" class="form-control" placeholder="Monto $" title="Monto Máximo Cobertura ($)">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 h-100" onclick="eliminarFila(this, '.categoria-item')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-link btn-sm text-decoration-none text-success" onclick="agregarFilaCategoria()">
                            <i class="fa-solid fa-circle-plus"></i> Añadir límite para otra categoría
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary px-5">Guardar Plan Completo</button>
            </div>
        </form>
    </div>
<script>
function agregarFilaExamen() {
    const contenedor = document.getElementById('contenedor-examenes');
    const nuevaFila = document.querySelector('.examen-item').cloneNode(true);
    nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
    nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    contenedor.appendChild(nuevaFila);
}

function agregarFilaCategoria() {
    const contenedor = document.getElementById('contenedor-categorias');
    const nuevaFila = document.querySelector('.categoria-item').cloneNode(true);
    nuevaFila.querySelectorAll('input').forEach(input => input.value = '');
    nuevaFila.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    contenedor.appendChild(nuevaFila);
}

function eliminarFila(btn, selector) {
    const filas = document.querySelectorAll(selector);
    if (filas.length > 1) { // Evitar borrar la última fila
        btn.closest(selector).remove();
    }
}
</script>

<?php 
// Incluir los modales para agregar exámenes o categorías al catálogo
include 'app/pagos/modales_creacion.php';
?>