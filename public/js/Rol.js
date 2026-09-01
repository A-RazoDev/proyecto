document.addEventListener('DOMContentLoaded', function () {
    const tableElement = $('#tabla_roles');

    // Inicializar DataTable con AJAX
    const tablaRoles = tableElement.DataTable({
        ajax: {
            url: '/rol/listar',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'nombre_rol' },
            { data: 'descripcion' },
            { 
                data: 'activo',
                render: function (data) {
                    return data 
                        ? '<span class="badge badge-success">Activo</span>' 
                        : '<span class="badge badge-danger">Inactivo</span>';
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Evento para guardar nuevo rol
    const formAgregarRol = document.getElementById('form_agregar_rol');
    if (formAgregarRol) {
        formAgregarRol.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(formAgregarRol);
            if (!formData.has('activo')) {
                formData.append('activo', 'off');
            }

            fetch('/rol/guardar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#modal_agregar_rol').modal('hide');
                    formAgregarRol.reset();
                    
                    // Recargar los datos de la tabla dinámicamente
                    tablaRoles.ajax.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});