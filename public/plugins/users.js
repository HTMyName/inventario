// Call the dataTables jQuery plugin
$(document).ready(function () {
    $('#dataTable').dataTable({
        order: [[0, 'asc']],
        lengthMenu: [[10, 20, 30, -1], [10, 20, 30, "All"]],
        language: {
            "emptyTable": "No hay resultados.",
            "info": "Mostrando del _START_ al _END_ de (_TOTAL_) resultados.",
            "infoEmpty": "Mostrando 0 resultados.",
            "infoFiltered": "",
            "lengthMenu": "Mostrar _MENU_ resultados",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se han encontrado coincidencias.",
            "paginate": {
                "first": "Primera",
                "last": "Última",
                "next": "<i class='fas fa-angle-right'>",
                "previous": "<i class='fas fa-angle-left'>"
            },
            "aria": {
                "sortAscending": "Ordenación ascendente",
                "sortDescending": "Ordenación descendente"
            }
        },
        /*dom: 'Bfrtip',
        buttons: [
            'excel',
        ],*/
        responsive: true,
    });
});
$(document).ready(function () {
    $('#dataTable2').dataTable({
        order: [[0, 'asc']],
        lengthMenu: [[10, 20, 30, -1], [10, 20, 30, "All"]],
        language: {
            "emptyTable": "No hay resultados.",
            "info": "Mostrando del _START_ al _END_ de (_TOTAL_) resultados.",
            "infoEmpty": "Mostrando 0 resultados.",
            "infoFiltered": "",
            "lengthMenu": "Mostrar _MENU_ resultados",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se han encontrado coincidencias.",
            "paginate": {
                "first": "Primera",
                "last": "Última",
                "next": "<i class='fas fa-angle-right'>",
                "previous": "<i class='fas fa-angle-left'>"
            },
            "aria": {
                "sortAscending": "Ordenación ascendente",
                "sortDescending": "Ordenación descendente"
            }
        },
        /*dom: 'Bfrtip',
        buttons: [
            'excel',
        ],*/
        responsive: true,
    });
});
$(document).ready(function () {
    $('#dataTable3').dataTable({
        //order: [[0, 'desc']],
        lengthMenu: [[10, 20, 30, -1], [10, 20, 30, "All"]],
        language: {
            "emptyTable": "No hay resultados.",
            "info": "Mostrando del _START_ al _END_ de (_TOTAL_) resultados.",
            "infoEmpty": "Mostrando 0 resultados.",
            "infoFiltered": "",
            "lengthMenu": "Mostrar _MENU_ resultados",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "No se han encontrado coincidencias.",
            "paginate": {
                "first": "Primera",
                "last": "Última",
                "next": "<i class='fas fa-angle-right'>",
                "previous": "<i class='fas fa-angle-left'>"
            },
            "aria": {
                "sortAscending": "Ordenación ascendente",
                "sortDescending": "Ordenación descendente"
            }
        },
        /*dom: 'Bfrtip',
        buttons: [
            'excel',
        ],*/
        responsive: true,
    });
});