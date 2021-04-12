function getLogs() {

    let year = document.getElementById('year');
    let mes = document.getElementById('mes');
    let tipo = document.getElementById('type');
    let user = document.getElementById('worker');
    let aceptBtn = document.getElementById('aceptBtn');
    let result = document.getElementById('query_result');
    let label_gastos = document.getElementById('label_gastos');
    let total_gastos = 0;
    let htmlresult = '';
    aceptBtn.disabled = true;

    let selectedYear = year.options[year.selectedIndex].value;
    let selectedMes = mes.options[mes.selectedIndex].value;
    let selectedMesText = mes.options[mes.selectedIndex].text;
    let selectedTipo = tipo.options[tipo.selectedIndex].value;
    let selectedUser = user.options[user.selectedIndex].value;

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();

    } else {  // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            aceptBtn.disabled = false;
            if (this.responseText != []) {
                let myObj = JSON.parse(this.responseText);
                for (let i = 0; i < myObj.length; i++) {
                    let tipoTmp = myObj[i].tipo;
                    let detallesTmp = myObj[i].detalles;

                    if (selectedTipo == 'gasto'){
                        total_gastos += parseInt(detallesTmp);
                    }

                    switch (tipoTmp) {
                        case 'additem':
                            tipoTmp = 'Producto Añadido';
                            detallesTmp = detallesTmp.replaceAll(',', ' - ');
                            break;
                        case 'addtaller':
                            tipoTmp = 'Inventario a Taller';
                            detallesTmp = detallesTmp.replaceAll(',', ' - ');
                            break;
                        case 'addinventario':
                            tipoTmp = 'Taller a Inventario';
                            detallesTmp = detallesTmp.replaceAll(',', ' - ');
                            break;
                        case 'baja':
                            tipoTmp = 'Baja';
                            break;
                        case 'factura':
                            tipoTmp = 'Factura';
                            detallesTmp = '<a target="_blank" href="/taller/user_factura/' + detallesTmp + '"><input class="btn btn-default btn-sm" type="button" value="Ver"></a>';
                            break;
                        case 'pago':
                            tipoTmp = 'Pago Factura';
                            detallesTmp = detallesTmp.replace(',', ' - ');
                            break;
                        case 'deudapaga':
                            tipoTmp = 'Deuda Paga';
                            break;
                        case 'salario':
                            tipoTmp = 'Salario';
                            break;
                        case 'prestamo':
                            tipoTmp = 'Prestamo';
                            break;
                        case 'gasto':
                            tipoTmp = 'Gasto';
                            detallesTmp = detallesTmp.replace(',', ' - ');
                            break;
                    }
                    let clienteTmp = myObj[i].cliente;
                    if (clienteTmp == null) {
                        clienteTmp = '-';
                    }
                    htmlresult +=
                        '<tr class="text-center">' +
                        '<td>' + myObj[i].fecha + '</td>' +
                        '<td>' + tipoTmp + '</td>' +
                        '<td>' + detallesTmp + '</td>' +
                        '<td>' + myObj[i].user + '</td>' +
                        '<td>' + clienteTmp + '</td></tr>';
                }
                if (selectedTipo == 'gasto'){
                    if (selectedMes != 'all'){
                        label_gastos.innerHTML = "Gastos del año " + selectedYear + " en " + selectedMesText + ": $" + total_gastos;
                    }else{
                        label_gastos.innerHTML = "Gastos del año " + selectedYear + ": $" + total_gastos;
                    }
                }
                if (htmlresult != ''){
                    result.innerHTML = htmlresult;
                }else{
                    result.innerHTML = '<tr class="text-center"><td colspan="5">Ningún resultado encontrado</td></tr>';
                }
            }


        }
    };

    xmlhttp.open("POST", '/logs/' + selectedYear + '/' + selectedMes + '/' + selectedTipo + '/' + selectedUser, true);
    xmlhttp.send();
}