$(document).ready(function () {
    // AUTH -- VALIDACION DE FORMULARIOS
    $(function () {
        formValidation();
    });
    //  FIN DE AUTH
    //btn_ingreso
    $(document).on("click", "#btn_ingreso", function () {
        logi = $("#txt_usua").val();
        pass = sha1($("#txt_pass").val().toString());
        if (logi != "" && pass != "") {
            $.ajax({
                url: base_url + 'auth/login',
                async: false,
                type: 'POST',
                data: {
                    logi: logi,
                    pass: pass.toString()
                },
                success: function (resu) {
                    switch (resu) {
                        case "0":
                            alertify.notify('El usuario o la clave no son correctos', 'error', 5, null);
                            break;                        
                        case "1":
                            redireccion("inicio");
                            break;
                        case "2":
                            alertify.notify('Este Usuario se encuentra activo', 'error', 5, null);
                            break;                            
                        default:
                            alertify.notify('El usuario o la clave no son correctos', 'error', 5, null);
                    }
                }
            });
        }
    });

});

function aMays(e, elemento) {
    tecla = (document.all) ? e.keyCode : e.which;
    elemento.value = elemento.value.toUpperCase();
}

function aMins(e, elemento) {
    tecla = (document.all) ? e.keyCode : e.which;
    elemento.value = elemento.value.toLowerCase();
}

function redireccion(contr, meth) {
    location.replace(base_url + contr + (meth ? "/" + meth : ""));
}

function sha1(valo) {
    return CryptoJS.SHA1(valo);
}