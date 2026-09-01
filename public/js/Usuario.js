$(document).ready(function () {
    // Inicializar DataTable con AJAX
    const tablaUsuarios = $('#tabla_usuarios').DataTable({
        ajax: {
            url: '/usuario/listar',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { 
                data: 'rol',
                render: function (data) {
                    return `<span class="badge badge-info">${data}</span>`;
                }
            },
            { data: 'nombre_completo' },
            { data: 'correo_electronico' },
            { data: 'nombre_usuario' },
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

    // Guardar / Editar usuario
    const formAgregarUsuario = document.getElementById('form_agregar_usuario');
    if (formAgregarUsuario) {
        formAgregarUsuario.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(formAgregarUsuario);
            if (!formData.has('activo')) {
                formData.append('activo', 'off');
            }

            fetch('/usuario/guardar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#modal_agregar_usuario').modal('hide');
                    formAgregarUsuario.reset();

                    // Recargar tabla de DataTables
                    tablaUsuarios.ajax.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // Evento al hacer clic en el botón Editar
    $('#tabla_usuarios tbody').on('click', '.btn-editar', function () {
        const rowData = tablaUsuarios.row($(this).closest('tr')).data();

        // Llenar el modal con los datos del registro seleccionado
        $('#usuario_id').val(rowData.id);
        $('#nombre_completo').val(rowData.nombre_completo);
        $('#correo_electronico').val(rowData.correo_electronico);
        $('#nombre_usuario').val(rowData.nombre_usuario);
        $('#activo').prop('checked', rowData.activo);
        
        // La clave de acceso no es obligatoria al editar
        $('#clave_acceso').removeAttr('required');

        // Cambiar título del modal
        $('#modalUsuarioLabel').text('Editar Usuario');
        
        // Abrir modal
        $('#modal_agregar_usuario').modal('show');
    });

    // Limpiar modal al cerrarlo
    $('#modal_agregar_usuario').on('hidden.bs.modal', function () {
        $('#form_agregar_usuario')[0].reset();
        $('#usuario_id').val('');
        $('#clave_acceso').attr('required', true);
        $('#modalUsuarioLabel').text('Nuevo Usuario');
    });
});