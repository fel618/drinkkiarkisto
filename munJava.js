function lisaaAines() {

    var container = document.getElementById("ainesLista");

    var div = document.createElement("div");

    div.innerHTML =
        "<select name='aines[]'>" +
        document.getElementById("ainesTemplate").innerHTML +
        "</select>" +
        " Määrä: <input type='text' name='maara[]'><br><br>";

    container.appendChild(div);
}