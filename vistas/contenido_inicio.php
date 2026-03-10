<?php

include_once 'C:/xampp/htdocs/IPSPUPTM/config/estadistica.php';
?>
<div class="card shadow-lg " style="background-color: #ffffffff;">
    <div class="cont-general m-3">
        <br>
         <h1 class="fw-bold text-center" style="color: #062974;">Estadísticas de la Institución IPSPUPTM-KR</h1>
            <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">

        <br>

        <div class="row">
            
            <div class="col-md-3 p-3">

                <div class="card text-center mb-3 text-white" style="background-color: #0e213d;">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-person fa-3x me-3"></i>
                        <div class="text-center">
                            <h5 class="card-title"><strong>Afiliados</strong></h5>
                            <p class="card-text display-4"><?php echo $total_afiliados; ?></p>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-md-3 p-3">
                <div class="card text-center mb-3 text-white" style="background-color: #062974;">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-people-arrows fa-3x me-3"></i>
                        <div class="text-center">
                            <h5 class="card-title"><strong>Beneficiarios</strong></h5>
                            <p class="card-text display-4"><?php echo $total_beneficiarios; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 p-3">
                <div class="card text-center mb-3 text-white" style="background-color: #0e213d;">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-user-nurse fa-3x me-3"></i>
                        <div class="text-center">
                            <h5 class="card-title"><strong>Citas</strong></h5>
                            <span class="card-text display-4"><?php echo $total_citas; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 p-3">
                <div class="card text-center mb-3 text-white" style="background-color: #062974;">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-house fa-3x me-3"></i>
                        <div class="text-center">
                            <h5 class="card-title"><strong>Comu.UPTM</strong></h5>
                            <span class="card-text display-4"><?php echo $total_citas_uptm; ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<br>

<div class="row">
    <div class="col-md-3 p-3">
        <div class="card shadow-lg  " style="background-color: #ffffffff;">



            <div class="text-center mt-3 mx-3">
                <h5 class="card-title"><strong>Distribución de Pacientes</strong></h5>
                <div style="position: relative; height:250px; width:100%">
                    <canvas id="chartPacientes"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5 p-3">
        <div class="card shadow-lg  " style="background-color: #ffffffff;">



            <div class="card text-center mt-3 mx-3 shadow-sm">
    <div class="card-body">
        <h5 class="card-title"><strong>Pacientes en el Mes</strong></h5>
        <div style="position: relative; height: 200px;">
            <canvas id="chartProgreso"></canvas>
            <div style="position: absolute; top: 60%; left: 50%; transform: translate(-50%, -50%);">
                <span class="display-4" style="color: #0e213d; font-weight: bold;">
                    <?php echo $total_citas ?>
                </span>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>

    <div class="col-md-4 p-3">
        <div class="card shadow-lg  " style="background-color: #ffffffff;">



            <div class="text-center mt-3 mx-3">
                <h5 class="card-title"><strong>Especialidades más visitadas</strong></h5>
                <div style="position: relative; height:300px; width:100%;">
                    <canvas id="chartEspecialidades"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="./recursos/chart.js"></script>
    <script>
    const ctx = document.getElementById('chartPacientes').getContext('2d');

    // Pasamos la variable de PHP a JavaScript
    const totalAfiliados = <?php echo $total_afiliados; ?>;
    const totalBeneficiarios = <?php echo $total_beneficiarios; ?>;
    const totalcomunidaduptm = <?php echo $total_citas_uptm; ?>;


    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Afiliados', 'Beneficiarios', 'Comunidad UPTM'],
            datasets: [{
                data: [totalAfiliados, totalBeneficiarios, totalcomunidaduptm], // Los valores
                backgroundColor: [
                    '#0e213d', // Verde (Bootstrap Success)
                    '#062974', // Azul (Bootstrap Info)
                    '#27b1f1' // Gris claro
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    </script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    const ctxBar = document.getElementById('chartEspecialidades').getContext('2d');

    // Pasar las variables PHP a variables JavaScript
    const ginecologia = <?php echo $citas_ginecologia; ?>;
    const medicinaInterna = <?php echo $citas_medicina_interna; ?>;
    const odontologia = <?php echo $citas_odontologia; ?>;
    const oftalmologia = <?php echo $citas_oftalmologia; ?>;
    const gastroenterologia = <?php echo $citas_gastroenterologia; ?>;
    const imagenologia = <?php echo $citas_imagenologia; ?>;

    const etiquetasEspecialidades = ['Odontología', 'Ginecología', 'Imagenología', 'Gastroenterología', 'Medicina Interna', 'Oftalmología'];
    const datosVisitas = [odontologia, ginecologia, imagenologia, gastroenterologia, medicinaInterna, oftalmologia]; 

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: etiquetasEspecialidades,
            datasets: [{
                label: 'Visitas',
                data: datosVisitas,
                backgroundColor: ['#0e213d', '#062974', '#27b1f1', '#58bcff', '#a8d0ff', '#cfe2ff'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Esto requiere que el padre tenga altura fija
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
<script>
    const ctxProgreso = document.getElementById('chartProgreso').getContext('2d');
    const total = <?php echo $total_citas ?>;
    const meta = 100; // Define una meta lógica para tu sistema

    new Chart(ctxProgreso, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [total, meta - total > 0 ? meta - total : 0],
                backgroundColor: ['#0e213d', '#e9ecef'],
                borderWidth: 0,
                circumference: 180, // Medio círculo
                rotation: 270,      // Orientación hacia arriba
                cutout: '80%'       // Grosor de la línea
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            }
        }
    });
</script>
