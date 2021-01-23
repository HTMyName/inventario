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
