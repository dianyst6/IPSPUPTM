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