<?php

include 'C:/xampp/htdocs/IPSPUPTM/config/database.php';


$sql_ext = "SELECT COUNT(*) AS total 
            FROM citas c
            INNER JOIN citas_uptm h ON c.id_cita = h.idcita 
            LEFT JOIN pagos_externos p ON c.id_cita = p.id_cita
            WHERE p.id_pago_ext IS NULL";
$res_ext = mysqli_query($conn, $sql_ext);
$pendientes_externos = mysqli_fetch_assoc($res_ext)['total'];

// Obtener el mes actual para la tarjeta de contrato
setlocale(LC_TIME, 'es_ES.UTF-8');
$mes_actual = date('F'); // Esto da el mes en inglés, podrías traducirlo o dejarlo así
?>

<div class="p-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold" style="color: #062974; letter-spacing: 1px;">Administración de Pagos</h1>
        <p class="text-muted">Seleccione el módulo de recaudación que desea gestionar</p>
        <hr class="mx-auto" style="width: 60px; height: 4px; background-color: #062974; border-radius: 2px; opacity: 1;">
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8 mb-4">
            <div class="card border-0 border-start border-success border-5 bg-light shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-white p-3 rounded-circle shadow-sm me-4">
                            <i class="fa-solid fa-user-doctor fs-1 text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="card-title h4 fw-bold mb-1" style="color: #2c3e50;">Pagos de Externos</h3>
                            <p class="text-secondary mb-2">Comunidad UPTM y pacientes particulares. Gestión de cobros por servicios médicos.</p>
                            <div class="d-flex align-items-center">
                                <span class="badge rounded-pill bg-success px-3">
                                    <i class="fas fa-clock me-1"></i> <?php echo $pendientes_externos; ?> Pendientes
                                </span>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <a href="/IPSPUPTM/home.php?vista=gestionpagosexternos" class="btn btn-success btn-lg rounded-pill px-4 shadow-sm fw-bold text-nowrap">
                                Gestionar <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-10 col-xl-8 mb-4">
            <div class="card border-0 border-start border-primary border-5 bg-light shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-white p-3 rounded-circle shadow-sm me-4">
                            <i class="fa-solid fa-file-invoice-dollar fs-1 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="card-title h4 fw-bold mb-1" style="color: #2c3e50;">Pagos por Contrato</h3>
                            <p class="text-secondary mb-2">Afiliados y convenios institucionales. Control de cuotas y estados de cuenta.</p>
                            <div class="d-flex align-items-center">
                                <span class="badge rounded-pill bg-primary px-3">
                                    <i class="fas fa-calendar-check me-1"></i> Mes: <?php echo date('M'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="ms-auto">
                            <a href="/IPSPUPTM/home.php?vista=gestionpagoscontrato" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm fw-bold text-nowrap">
                                Gestionar <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .flex-shrink-0 i {
        width: 50px;
        text-align: center;
    }
</style>