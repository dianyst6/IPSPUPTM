<div class="card shadow-lg">
    <div id="cont-general m-3" class="container-fluid mt-5 text-center ">
         <h1 class="fw-bold text-center" style="color: #062974;">Reportes</h1>
            <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">
        <h2 class="text-center mb-4">Esta es la sección de reportes, puedes elegir si descargar estos mismos en formato
            PDF, Word o Excel. Dale click al ícono del formato en que deseas descargar el reporte</h2>
        <br>
        <div class="row text-center" id="initialIcons">
            <div class="col-md-6 mb-4">
                <button class="btn btn-danger btn-lg" onclick="showPDFOptions()">
                    <i class="fas fa-file-pdf fa-5x"></i>
                    <p class="mt-3">Descargar PDF</p>
                </button>
            </div>

            <div class="col-md-6 mb-4">
                <button class="btn btn-success btn-lg" onclick="showExcelOptions()">
                    <i class="fas fa-file-excel fa-5x"></i>
                    <p class="mt-3">Descargar Excel</p>
                </button>
            </div>
        </div>
        <div id="pdfOptions" class="d-none d-flex flex-column align-items-center">
            <div class="mb-3 w-50 text-center">
                <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                    data-bs-target="#afiliadosOptionsPDF" aria-expanded="false" aria-controls="afiliadosOptions">
                    Descargar reporte de Afiliados (PDF)
                </button>
                <div class="collapse mt-2 w-100" id="afiliadosOptionsPDF">
                    <div class="d-flex justify-content-center">
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_afiliados.php"
                            class="btn btn-outline-primary btn-lg"
                            onclick="mostrarAlertaDescarga('PDF', 'Afiliados')">Ver Reporte</a>
                    </div>
                </div>
            </div>

            <div class="mb-3 w-50 text-center">
                <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                    data-bs-target="#beneficiariosOptionsPDF" aria-expanded="false"
                    aria-controls="beneficiariosOptions">
                    Descargar reporte de Beneficiarios por Afiliado (PDF)
                </button>
                <div class="collapse mt-2 w-100" id="beneficiariosOptionsPDF">
                    <div class="d-flex justify-content-center">
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_beneficiarios.php"
                            class="btn btn-outline-primary btn-lg"
                            onclick="mostrarAlertaDescarga('PDF', 'Beneficiarios')">Ver Reporte</a>
                    </div>
                </div>
            </div>

            <div class="mb-3 w-50 text-center">
                <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                    data-bs-target="#citasOptionsPDF" aria-expanded="false" aria-controls="citasOptions">
                    Descargar reporte de Citas (PDF)
                </button>
                <div class="collapse mt-2 w-100" id="citasOptionsPDF">
                    <div class="d-flex justify-content-center flex-column align-items-center">
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_citas.php?tipo_reporte=semanal"
                            class="btn btn-outline-primary btn-lg mb-2"
                            onclick="mostrarAlertaDescarga('PDF', 'Citas Semanal')">Semanal</a>
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_citas.php?tipo_reporte=quincenal"
                            class="btn btn-outline-primary btn-lg mb-2"
                            onclick="mostrarAlertaDescarga('PDF', 'Citas Quincenal')">Quincenal</a>
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_citas.php?tipo_reporte=mensual"
                            class="btn btn-outline-primary btn-lg"
                            onclick="mostrarAlertaDescarga('PDF', 'Citas Mensual')">Mensual</a>
                    </div>
                </div>
            </div>

            <div class="mb-3 w-50 text-center">
                <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                    data-bs-target="#especialidadesOptionsPDF" aria-expanded="false"
                    aria-controls="especialidadesOptions">
                    Descargar reporte de Especialidades (PDF)
                </button>
                <div class="collapse mt-2 w-100" id="especialidadesOptionsPDF">
                    <div class="d-flex justify-content-center flex-column align-items-center">
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_especialidades.php?tipo_reporte=semanal"
                            class="btn btn-outline-primary btn-lg mb-2"
                            onclick="mostrarAlertaDescarga('PDF', 'Especialidades Semanal')">Semanal</a>
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_especialidades.php?tipo_reporte=quincenal"
                            class="btn btn-outline-primary btn-lg mb-2"
                            onclick="mostrarAlertaDescarga('PDF', 'Especialidades Quincenal')">Quincenal</a>
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_especialidades.php?tipo_reporte=mensual"
                            class="btn btn-outline-primary btn-lg"
                            onclick="mostrarAlertaDescarga('PDF', 'Especialidades Mensual')">Mensual</a>
                    </div>
                </div>
            </div>

            <div class="mb-3 w-50 text-center">
                <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                    data-bs-target="#pagosOptionsPDF" aria-expanded="false" aria-controls="pagosOptions">
                    Descargar reporte de Pagos (PDF)
                </button>
                <div class="collapse mt-2 w-100" id="pagosOptionsPDF">
                    <div class="d-flex justify-content-center flex-column align-items-center">
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_pagos.php?tipo_pago=contrato"
                            class="btn btn-outline-primary btn-lg mb-2"
                            onclick="mostrarAlertaDescarga('PDF', 'Pagos de Contratos')">Pagos de Contratos</a>
                        <a href="/IPSPUPTM/app/reportes/pdf/reporte_pagos.php?tipo_pago=externo"
                            class="btn btn-outline-primary btn-lg"
                            onclick="mostrarAlertaDescarga('PDF', 'Pagos Externos')">Pagos Externos</a>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div id="excelOptions" class="d-none d-flex flex-column align-items-center">
        <div class="mb-3 w-50 text-center">
            <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                data-bs-target="#afiliadosOptionsExcel" aria-expanded="false" aria-controls="afiliadosOptions">
                Descargar reporte de Afiliados (EXCEL)
            </button>
            <div class="collapse mt-2" id="afiliadosOptionsExcel">
                <div class="d-grid gap-2">
                    <a href="/IPSPUPTM/app/reportes/excel/reporte_afiliados.php" class="btn btn-outline-primary btn-lg"
                        onclick="mostrarAlertaDescarga('Excel', 'Afiliados')">Ver Reporte</a>
                </div>
            </div>
        </div>

        <div class="mb-3 w-50 test-center">
            <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                data-bs-target="#beneficiariosOptionsExcel" aria-expanded="false" aria-controls="beneficiariosOptions">
                Descargar reporte de Beneficiarios por Afiliado (EXCEL)
            </button>
            <div class="collapse mt-2" id="beneficiariosOptionsExcel">
                <div class="d-grid gap-2">
                    <a href="/IPSPUPTM/app/reportes/excel/reporte_beneficiarios.php"
                        class="btn btn-outline-primary btn-lg"
                        onclick="mostrarAlertaDescarga('Excel', 'Beneficiarios')">Ver Reporte</a>
                </div>
            </div>
        </div>

        <div class="mb-3 w-50 text-center">
            <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                data-bs-target="#citasOptionsExcel" aria-expanded="false" aria-controls="citasOptions">
                Descargar reporte de Citas (EXCEL)
            </button>
            <div class="collapse mt-2" id="citasOptionsExcel">
                <div class="d-grid gap-2">
                    <a href="/IPSPUPTM/app/reportes/excel/reporte_citas.php?tipo_reporte=semanal"
                        class="btn btn-outline-primary btn-lg"
                        onclick="mostrarAlertaDescarga('Excel', 'Citas Semanal')">Semanal</a>
                    <a href="/IPSPUPTM/app/reportes/excel/reporte_citas.php?tipo_reporte=quincenal"
                        class="btn btn-outline-primary btn-lg"
                        onclick="mostrarAlertaDescarga('Excel', 'Citas Quincenal')">Quincenal</a>
                    <a href="/IPSPUPTM/app/reportes/excel/reporte_citas.php?tipo_reporte=mensual"
                        class="btn btn-outline-primary btn-lg"
                        onclick="mostrarAlertaDescarga('Excel', 'Citas Mensual')">Mensual</a>
                </div>
            </div>
        </div>

        <div class="mb-3 w-50 text-center">
            <button class="btn btn-primary btn-lg w-100" type="button" data-bs-toggle="collapse"
                data-bs-target="#especialidadesOptionsExcel" aria-expanded="false"
                aria-controls="especialidadesOptions">
                Descargar reporte de Especialidades (EXCEL)
            </button>
            <div class="collapse mt-2" id="especialidadesOptionsExcel">
                <div class="d-grid gap-2">
                    <a href="/IPSPUPTM/app/reportes/excel/reporte_especialidades.php?tipo_reporte=semanal"
                        class="btn btn-outline-primary btn-lg"
                        onclick="mostrarAlertaDescarga('Excel', 'Especialidades Semanal')">Semanal</a>
                    <a href="/IPSPUPTM/app/reportes/excel/reporte_especialidades.php?tipo_reporte=quincenal"
                        class="btn btn-outline-primary btn-lg"
                        onclick="mostrarAlertaDescarga('Excel', 'Especialidades Quincenal')">Quincenal</a>
                    <a href="/IPSPUPTM/app/reportes/excel/reporte_especialidades.php?tipo_reporte=mensual"
                        class="btn btn-outline-primary btn-lg"
                        onclick="mostrarAlertaDescarga('Excel', 'Especialidades Mensual')">Mensual</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="/IPSPUPTM/assets/js/reportes.js"></script>