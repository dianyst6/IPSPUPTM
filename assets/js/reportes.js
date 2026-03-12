function showPDFOptions() {
  document.getElementById('initialIcons').classList.add('d-none'); // Ocultar íconos iniciales
  document.getElementById('pdfOptions').classList.remove('d-none'); // Mostrar opciones de PDF
}

function showExcelOptions() {
  document.getElementById('initialIcons').classList.add('d-none'); // Ocultar íconos iniciales
  document.getElementById('excelOptions').classList.remove('d-none'); // Mostrar opciones de Excel
}

function showWordOptions() {
  document.getElementById('initialIcons').classList.add('d-none'); // Ocultar íconos iniciales
  document.getElementById('wordOptions').classList.remove('d-none'); // Mostrar opciones de Excel
}

function mostrarAlertaDescarga(formato, reporte) {
  alertify.message(`Se está descargando el reporte de ${reporte} en formato ${formato}.`, 2, function(){});
}

function generarReportePago(tipo) {
  const fechaInicio = document.getElementById('fecha_inicio_pago').value;
  const fechaFin = document.getElementById('fecha_fin_pago').value;
  
  let url = `/IPSPUPTM/app/reportes/pdf/reporte_pagos.php?tipo_pago=${tipo}`;
  
  if (fechaInicio && fechaFin) {
    url += `&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;
  }
  
  mostrarAlertaDescarga('PDF', tipo === 'contrato' ? 'Pagos de Contratos' : 'Pagos Externos');
  window.location.href = url;
}