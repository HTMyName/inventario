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


function ganancia() {
    let precioC = document.getElementById('add_item_precioC');
    let precioV = document.getElementById('add_item_precioV');

    document.getElementById('auto-ganacias').innerHTML = precioV.value - precioC.value;

}

