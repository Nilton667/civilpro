//Permissão para requisição
let permitir = 0;

//Carregamento da pagina
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown',loginCheck);
});
function loginCheck(e){
    if(e.keyCode == 13){
        login();
    }
}

//Login
document.querySelector('#entrar').addEventListener('click',function(){
    login();    
});

function login() {

    if(permitir == 0){
        permitir = 1;
    }else{
        return;
    }

    var email = document.getElementById('email');
    var senha = document.getElementById('senha');
    var check = document.getElementById('sessionCashe');

    if(email.value.trim().length < 6 || email.value.search('@') == -1){
        $.toast({
            heading: 'Alerta',
            text: 'Insira um email valido!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        email.focus();
        permitir = 0;
        return;
    }else if(senha.value.trim().length < 6){
        $.toast({
            heading: 'Alerta',
            text: 'A senha deve ter no minímo 6 caracteres!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        senha.focus();
        permitir = 0;
        return;
    }else if(check.checked){
        check = 1;
    }else{
        check = 0;
    }

    $.ajax({
        url  : "app/api/login",
        type : 'post',
        data : {
        login  : true,
        header      : 'application/json',
        email       : email.value.trim(),
        senha       : senha.value.trim(),
        checkbox    : check,
        dispositivo : navigator.appName,
    },
    dataType: 'json',
    beforeSend : function(){
        load();
    }})
    .done(function(msg){
        
        if (msg == 1) {
            $.toast({
                heading: 'Alerta',
                text: 'Login efectuado com sucesso!',
                icon: 'info',
                loader: true,
                loaderBg: '#0088bd'
            });
            setTimeout(function(){ location.reload(); }, 3000);
            return;           
        }else{
            $.toast({
                heading: 'Alerta',
                text: msg,
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });  
        }

        permitir = 0;
        onload();

    })
    .fail(function(jqXHR, textStatus, msg){
        onload();
        permitir = 0;
        $.toast({
            heading: 'Alerta',
            text: msg,
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
    });
    
}