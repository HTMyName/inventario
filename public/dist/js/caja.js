function vaciarCaja(id, name) {
    if (confirm("¿Estás seguro de vaciar la caja de " + name + "? Esta acción no se puede revertir.")) {

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

        xmlhttp.open("POST", "home/vaciarcaja/" + id, true);
        xmlhttp.send();

    }
}

function showTodo(id) {
    let li = document.getElementsByClassName("todol" + id);
    let all_li = document.getElementsByClassName("li-item-class");
    let todo_number = document.getElementById('todonumber');

    for (let i = 0; i < all_li.length; i++) {
        let li_hide = all_li[i];
        if (!li_hide.classList.contains('d-none')) {
            li_hide.classList.add('d-none');
        }
    }

    for (let i = 0; i < li.length; i++) {
        let li_show = li[i];
        if (li_show.classList.contains('d-none')) {
            li_show.classList.remove('d-none');
        }
    }

    todo_number.innerHTML = id;
}

function completarTodo(id) {

    let checkbox = document.getElementById('todoCheck' + id);

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();

    } else {  // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //window.location.assign("home");
        }
    };

    if (checkbox.checked){
        if (confirm("¿Quieres completar esta Tarea?")) {
            xmlhttp.open("GET", "home/todo/completar/" + 1 + '/' + id, true);
            xmlhttp.send();
        }else{
            checkbox.checked = false;
            setTimeout(removeDone, 1, checkbox.parentElement.parentElement.parentElement);
        }
    }else{
        xmlhttp.open("GET", "home/todo/completar/" + 0 + '/' + id, true);
        xmlhttp.send();
    }

}

function removeDone(ele){
    ele.classList.remove('done');
}