function vaciarCaja() {
    if (confirm("¿Estás seguro de vaciar la caja? Esta acción no se puede revertir.")) {

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();

        } else {  // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                window.location.assign("home");
            }
        };

        xmlhttp.open("POST", "home/vaciarcaja", false);
        xmlhttp.send();

    }
}