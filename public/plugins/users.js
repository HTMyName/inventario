// Call the dataTables jQuery plugin
$(document).ready(function () {
    $('#dataTable').dataTable({
        order: [[0, 'asc']],
        lengthMenu: [[10, 20, 30, -1], [10, 20, 30, "All"]],
        /*dom: 'Bfrtip',
        buttons: [
            'excel',
        ],*/
        responsive: true,
    });
});