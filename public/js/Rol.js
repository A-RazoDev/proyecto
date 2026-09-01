$(document).ready(function () {
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
            },
            {
                // Columna de Acciones (Botón Editar)
                data: null,
                orderable: false,
                render: function (data, type, row) {
                    return `
                        <button type="button" class="btn btn-warning btn-sm btn-editar" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                    `;
                }
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Evento para guardar o actualizar rol
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

    // Evento al hacer clic en el botón Editar
    $('#tabla_roles tbody').on('click', '.btn-editar', function () {
        const rowData = tablaRoles.row($(this).closest('tr')).data();

        // Llenar el modal con los datos seleccionados
        $('#rol_id').val(rowData.id);
        $('#nombre_rol').val(rowData.nombre_rol);
        $('#descripcion').val(rowData.descripcion);
        $('#activo').prop('checked', rowData.activo);

        // Cambiar título del modal
        $('#modalRolLabel').text('Editar Rol');
        
        // Abrir modal
        $('#modal_agregar_rol').modal('show');
    });

    // Limpiar modal al cerrarlo
    $('#modal_agregar_rol').on('hidden.bs.modal', function () {
        $('#form_agregar_rol')[0].reset();
        $('#rol_id').val('');
        $('#modalRolLabel').text('Nuevo Rol');
    });
});