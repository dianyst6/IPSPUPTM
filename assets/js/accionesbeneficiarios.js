// Función para cargar datos en el modal
function cargarDatosModal(modal, cedula) {
  console.log("Cédula obtenida para cargar datos: ", cedula);

  let inputcedula = modal.querySelector('#cedula');
  let inputnombre = modal.querySelector('#nombre');
  let inputapellido = modal.querySelector('#apellido');
  let inputfechanacimiento = modal.querySelector('#fechanacimiento');
  let inputgenero = modal.querySelector('#genero'); // El select de género
  let inputtelefono = modal.querySelector('#telefono');
  let inputcorreo = modal.querySelector('#correo');
  let inputocupacion = modal.querySelector('#ocupacion');
  let inputparentesco = modal.querySelector('#parentesco');
  let selectAfiliado = modal.querySelector('#cedula_afil'); // El select de afiliados

  let url = "/IPSPUPTM/app/beneficiarios/getbeneficiarios.php";

  let formData = new FormData();
  formData.append('cedula', cedula);

  fetch(url, {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.error) {
      console.error("Error al cargar datos: ", data.error);
    } else {
      console.log("Datos cargados:", data);
      inputcedula.value = data.cedula || '';
      inputnombre.value = data.nombre || '';
      inputapellido.value = data.apellido || '';
      inputfechanacimiento.value = data.fechanacimiento || '';
      inputtelefono.value = data.telefono || '';
      inputcorreo.value = data.correo || '';
      inputocupacion.value = data.ocupacion || '';
      if (inputparentesco) inputparentesco.value = data.parentesco || '';

      // Para el modal de visualización
      if (modal.id === 'vermodal') {
        inputgenero.value = data.genero || '';
      }

      // Para el modal de edición, establece el género y el afiliado
      if (modal.id === 'editmodal') {
        inputgenero.value = data.genero || '';

        // Selecciona el afiliado relacionado
        if (selectAfiliado) {
          for (let i = 0; i < selectAfiliado.options.length; i++) {
            if (selectAfiliado.options[i].value == data.id_afiliado) {
              selectAfiliado.selectedIndex = i;
              break;
            }
          }
        }

        // Agrega el event listener para el formulario de edición aquí
        const formularioEditarBeneficiario = modal.querySelector('form');
        if (formularioEditarBeneficiario) {
          formularioEditarBeneficiario.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene la recarga de la página
            console.log("Formulario de edición enviado");

            const formDataEditar = new FormData(formularioEditarBeneficiario);

            fetch('/IPSPUPTM/app/beneficiarios/modales/actualizar/actualizar.php', {  // Asegúrate de que esta ruta es correcta
              method: 'POST',
              body: formDataEditar
            })
            .then(response => response.json())
            .then(data => {
              console.log("Respuesta del servidor:", data);
              if (data.success) {
                alertify.message(data.message, 3, function() {
                  window.location.reload(); // Recarga la página después de la actualización exitosa
                });
              } else {
                alertify.error(data.message); // Muestra el mensaje de error
              }
              bootstrap.Modal.getInstance(modal).hide();
            })
            .catch(error => {
              console.error("Error en la petición fetch:", error);
              alertify.error("Error de red al actualizar.");
              bootstrap.Modal.getInstance(modal).hide();
            });
          });
        }
      }
    }
  })
  .catch(error => console.error("Error de conexión: ", error));
}

// Modal de visualización
let vermodal = document.getElementById('vermodal');
if (vermodal) {
    vermodal.addEventListener('shown.bs.modal', event => {
      let button = event.relatedTarget;
      let cedula = button.getAttribute('data-bs-cedula');
      cargarDatosModal(vermodal, cedula);
    });
}

// Modal de edición
let editmodal = document.getElementById('editmodal');
if (editmodal) {
    editmodal.addEventListener('shown.bs.modal', event => {
      let button = event.relatedTarget;
      let cedula = button.getAttribute('data-bs-cedula');
      cargarDatosModal(editmodal, cedula); // Cargar datos para el modal de edición
    });
}


// Modal de eliminación
const eliminamodal = document.getElementById('eliminamodal');
if (eliminamodal) {
    eliminamodal.addEventListener('shown.bs.modal', event => {
        console.log("[Event - eliminamodal] El modal 'eliminamodal' se ha mostrado.");
        const button = event.relatedTarget;
        const cedula = button.getAttribute('data-bs-cedula');
        console.log("[Event - eliminamodal] Cédula obtenida del botón:", cedula);
        const cedulaInput = eliminamodal.querySelector('.modal-footer #cedula');
        if (cedulaInput) {
            cedulaInput.value = cedula;
            console.log("[Event - eliminamodal] Cédula asignada al campo oculto del modal de eliminación:", cedula);
        }
    });

    // Agrega este event listener para manejar el envío del formulario de eliminación
    const formularioEliminar = eliminamodal.querySelector('form'); // Asegúrate de seleccionar el formulario correcto
    if (formularioEliminar) {
        formularioEliminar.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene la recarga de la página
            console.log("[Event - eliminamodal] Formulario de eliminación enviado.");

            const formData = new FormData(formularioEliminar);

            fetch('/IPSPUPTM/app/beneficiarios/modales/eliminar/eliminar.php', { 
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("[Event - eliminamodal] Respuesta del servidor:", data);
                if (data.success) {
                    alertify.message(data.message, 3, function() {
                        window.location.reload(); // Recarga la página después de la eliminación exitosa
                    });
                } else {
                    alertify.error(data.message); // Muestra el mensaje de error
                }
                bootstrap.Modal.getInstance(eliminamodal).hide(); // Cierra el modal después de la respuesta
            })
            .catch(error => {
                console.error("[Event - eliminamodal] Error en la petición fetch:", error);
                alertify.error("Error de red al eliminar.");
                bootstrap.Modal.getInstance(eliminamodal).hide();
            });
        });
    }
}



// Filtro de búsqueda en la tabla
const searchInput = document.getElementById('search'); // Campo de búsqueda
const tableBody = document.querySelector('.table tbody'); // Cuerpo de la tabla

searchInput.addEventListener('keyup', () => {
  const filter = searchInput.value.toLowerCase(); // Texto ingresado por el usuario
  const rows = tableBody.getElementsByTagName('tr'); // Filas de la tabla

  for (let i = 0; i < rows.length; i++) {
    const cells = rows[i].getElementsByTagName('td'); // Celdas de cada fila
    let match = false;

    // Recorre las celdas de cada fila buscando coincidencias
    for (let j = 0; j < cells.length; j++) {
      if (cells[j] && cells[j].textContent.toLowerCase().includes(filter)) {
        match = true;
        break;
      }
    }

    // Muestra u oculta la fila dependiendo de si hay coincidencias
    rows[i].style.display = match ? '' : 'none';
  }
});
