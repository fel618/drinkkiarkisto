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