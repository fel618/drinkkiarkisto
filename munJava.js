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