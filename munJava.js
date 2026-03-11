function lisaaAines(){

    const lista = document.getElementById("ainesLista");

    const uusi = document.createElement("div");
    uusi.className = "ainesRivi";

    const template = document.querySelector("select[name='aines[]']").innerHTML;

    uusi.innerHTML =
        "<select name='aines[]'>" +
        template +
        "</select> Määrä: <input type='text' name='maara[]'>";

    lista.appendChild(uusi);
}

function setSearch(type){

    document.getElementById("tyyppi").value = type;

    document.getElementById("btnNimi").classList.remove("active");
    document.getElementById("btnAines").classList.remove("active");

    if(type == "nimi"){
        document.getElementById("btnNimi").classList.add("active");
    }
    else{
        document.getElementById("btnAines").classList.add("active");
    }

}