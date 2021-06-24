function formSubmit($IdForm)
{
    event.preventDefault();
    let $form = $("#" + $IdForm);
    if(confirm('¿Estás seguro que deseas realizar esta acción?')){
        $form.submit();
    }
}


/*
function validar(){
    var titulo, nombre, app, apm, email, password, expresion;
    titulo   = document.getElementById("titulo").value;
    nombre   = document.getElementById("nombre").value;
    app      = document.getElementById("app").value;
    apm      = document.getElementById("apm").value;
    email    = document.getElementById("email").value;
    password = document.getElementById("password").value;

    expresion = /^([A-Za-z 0-9]){8}$/;

    if(titulo === "" || nombre === "" || app === "" || apm === "" || email === "" || password === ""){
        alert("Los campos son obligatorios");
        return false;
    }
    else if(titulo.length>4){
        alert("El titulo debe ser abreviado y con punto");
        return false;
    }
    else if(nombre.length>30){
        alert("El nombre admite solo 30 caracteres");
        return false;
    }
    else if(app.length>30){
        alert("El apellido paterno admite solo 30 caracteres");
        return false;
    }
    else if(apm.length>30){
        alert("El apellido materno admite solo 30 caracteres");
        return false;
    }
    else if(email.length>100){
        alert("El correo es muy largo");
        return false;
    }

    else if(password.length>8){
        alert("La contraseña admite solo 8 caracteres");
        return false;
    }
    else if(!expresion.test(password)){
        alert("La contraseña no es valida");
        return false;
    }
}

//function validar_email(valor){
   /* re=/^([\da-z_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/; */
  /* re=/^([\da-z_\-.]+)@([\da-z\-.]+)\.([a-z\-.]{1,})$/i;
  if(!re.exec(valor)){
    return false;
  }else{
    return true;
  }
}
*/
