function delUser(id, name) {

    if (confirm("¿Estás seguro de eliminar al usuario " + name + "? Esta acción no se puede revertir.")) {

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();

        } else {  // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                window.location.assign("users");
            }
        };

        xmlhttp.open("GET", "users/delete/" + id, false);
        xmlhttp.send();

    }
}

function delItem(id, marca, modelo) {

    if (confirm("¿Estás seguro de eliminar el producto de marca " + marca + " modelo: " + modelo + "? Esta acción no se puede revertir.")) {

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();

        } else {  // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                window.location.assign("items");
            }
        };

        xmlhttp.open("GET", "items/delete/" + id, false);
        xmlhttp.send();

    }
}

function delClient(id, name) {

    if (confirm("¿Estás seguro de eliminar el cliente " + name + "? Esta acción no se puede revertir.")) {

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();

        } else {  // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                window.location.assign("client");
            }
        };

        xmlhttp.open("GET", "client/delete/" + id, false);
        xmlhttp.send();

    }
}

function delServicio(id, name) {

    if (confirm("¿Estás seguro de eliminar el servicio " + name + "? Esta acción no se puede revertir.")) {

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();

        } else {  // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                window.location.assign("services");
            }
        };

        xmlhttp.open("GET", "services/delete/" + id, false);
        xmlhttp.send();

    }
}

function ganancia() {
    let precioC = document.getElementById('add_item_precioC');
    let precioV = document.getElementById('add_item_precioV');

    document.getElementById('auto-ganacias').innerHTML = (precioV.value - precioC.value).toFixed(2);

}


function getClient(event) {

    let run = true;
    let xmlhttp;

    //console.log(event.keyCode);
    if (event.keyCode === undefined) {
        run = false;
    }
    if (event.keyCode === 37) {
        run = false;
    }
    if (event.keyCode === 38) {
        run = false;
    }
    if (event.keyCode === 39) {
        run = false;
    }
    if (event.keyCode === 40) {
        run = false;
    }

    if (run == true) {

        let client_datalist = document.getElementById('client_datalist');
        let data = document.getElementById('client_datalist_input').value;

        if (data !== "") {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();

            } else {  // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText !== "") {
                        client_datalist.innerHTML = this.responseText;
                    } else {
                        client_datalist.innerHTML = "<option value='null'>Sin resultados</option>";
                    }

                }
            };

            xmlhttp.open("POST", "get_user/" + data, true);
            xmlhttp.send();
        } else {
            client_datalist.innerHTML = "<option value='null'>Sin resultados</option>";
        }
    }
}

function aceptarClient() {
    let client_datalist_input = document.getElementById('client_datalist_input');
    let cliente_telefono = document.getElementById('cliente_telefono');
    let id_cliente_visible = document.getElementById('id_cliente_input');
    let id_cliente_hidden = document.getElementById('factura_id_cliente');
    let select = document.getElementById('client_datalist');
    let selectedOption = select.options[select.selectedIndex];

    //id_cliente_hidden.value = selectedOption.value;


    if (selectedOption.value != "null") {
        id_cliente_visible.value = selectedOption.text;
        cliente_telefono.value = selectedOption.id;
        id_cliente_hidden.innerHTML = '<option value="' + selectedOption.value + '">' + selectedOption.text + '</option>';
    } else {
        id_cliente_visible.value = client_datalist_input.value;
        cliente_telefono.value = "";
        id_cliente_hidden.innerHTML = '<option value=""></option>';
    }
}

function getProduct(id) {

    let fila = document.getElementById(id + "_product");
    let name = fila.childNodes[1].outerText;
    let model = fila.childNodes[3].outerText;
    let serie = fila.childNodes[5].outerText;
    let precio = fila.childNodes[7].outerText;
    let cant = fila.childNodes[9].outerText;

    let cookie = getCookie(id);

    console.log(cant + " cantidad");

    if (cookie != "") {
        cookie++;
        document.cookie = `${id}=${cookie}`;
    } else {
        document.cookie = `${id}=1`;
        cookie = 1;
    }

    console.log(cookie + " cokkie");

    if (cookie >= cant) {
        fila.style.display = "none";
    }

    let ul = document.getElementById('product-list');

    let li = document.createElement("LI");
    li.setAttribute("class", "list-group-item border-top-0 border-left-0 border-right-0");
    li.setAttribute("id", id + name);
    li.innerHTML = name + " " + model + " " + serie;

    let btn_del = document.createElement("BUTTON");
    btn_del.setAttribute("class", "btn btn-outline-danger btn-xs float-right");
    btn_del.setAttribute("style", "min-width: 30px");
    btn_del.setAttribute("type", "button");
    btn_del.setAttribute("onclick", "productRemove('" + id + "','" + name + "')");

    let icon = document.createElement("I");
    icon.setAttribute("class", "fas fa-minus");

    let producto = document.createElement("INPUT");
    producto.setAttribute("id", id + name + "input");
    producto.setAttribute("type", "hidden");
    producto.setAttribute("name", "productsArray[]");
    producto.setAttribute("value", "" + id + "");

    li.appendChild(btn_del);
    btn_del.appendChild(icon);
    ul.appendChild(li);
    ul.appendChild(producto);

}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function productRemove(id, name) {

    let fila = document.getElementById(id + "_product");
    let cookie_cant = getCookie(id);

    if (cookie_cant != "") {
        cookie_cant--;
        document.cookie = `${id}=${cookie_cant}`;
    }

    let list = document.getElementById(id + name);
    list.remove();
    let producto = document.getElementById(id + name + "input");
    producto.remove();
    fila.style.display = "table-row";
}

function getService(id) {

    let fila = document.getElementById(id + "_service");
    let name = fila.childNodes[1].outerText;

    let ul = document.getElementById('service-list');

    let li = document.createElement("LI");
    li.setAttribute("class", "list-group-item border-top-0 border-left-0 border-right-0");
    li.setAttribute("id", id + name + "service");
    li.innerHTML = name;

    let btn_del = document.createElement("BUTTON");
    btn_del.setAttribute("class", "btn btn-outline-danger btn-xs float-right");
    btn_del.setAttribute("style", "min-width: 30px");
    btn_del.setAttribute("type", "button");
    btn_del.setAttribute("onclick", "serviceRemove('" + id + "','" + name + "')");

    let icon = document.createElement("I");
    icon.setAttribute("class", "fas fa-minus");

    let servicio = document.createElement("INPUT");
    servicio.setAttribute("id", id + name + "inputservice");
    servicio.setAttribute("type", "hidden");
    servicio.setAttribute("name", "servicesArray[]");
    servicio.setAttribute("value", "" + id + "");

    li.appendChild(btn_del);
    btn_del.appendChild(icon);
    ul.appendChild(li);
    ul.appendChild(servicio);

}

function serviceRemove(id, name) {

    let list = document.getElementById(id + name + "service");
    list.remove();
    let servicio = document.getElementById(id + name + "inputservice");
    servicio.remove();

}

function delFactura(id) {

    let url = window.location.href;
    let url2 = url;

    if (url.includes("user_factura/")){
        url = url.replace('user_factura', 'eliminar_factura');
    }else if (url.includes("user_factura")){
        url = url.replace('user_factura', 'eliminar_factura/') + id;
    }

    if (confirm("¿Estás seguro de eliminar esta factura? Esta acción no se puede revertir.")) {

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();

        } else {  // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                window.location.assign(url2);
            }
        };

        xmlhttp.open("POST", url, false);
        xmlhttp.send();

    }
}