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
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Guardar nuevo usuario
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
});