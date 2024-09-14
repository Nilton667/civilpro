//Permissão para requisição
let permitir = 0;
    
//Carregamento da pagina
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown',getFunction);
});
function getFunction(e){
    if(e.keyCode == 13){
        //Verificar conta
        if (document.querySelector('#verificar')) {
            verificar();
            return;
        }
        //Verificar chave
        if (document.querySelector('#enviar')) {
            enviar();
            return;  
        }
        //Altera senha
        if (document.querySelector('#entrar')) {
            entrar();
            return;      
        }
    }
}

//Verificar conta
if (document.querySelector('#verificar')) {
    document.querySelector('#verificar').addEventListener('click',function(){
        verificar();    
    });   
}

function verificar() {

    if(permitir == 0){
        permitir = 1;
    }else{
        return;
    }

    var email = document.getElementById('email');

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
    }

    $.ajax({
        url  : "app/api/login",
        type : 'post',
        data : {
        recover  : true,
        header   : 'application/json',
        email    : email.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
        load();
    }})
    .done(function(msg){

        if (Array.isArray(msg)) {
            location.href = './recover?user='+msg[0]['user'];
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

//Verificar chave
if (document.querySelector('#enviar')) {
    document.querySelector('#enviar').addEventListener('click',function(){
        enviar();    
    });    
}

function enviar() {

    if(permitir == 0){
        permitir = 1;
    }else{
        return;
    }

    var email = document.getElementById('email');
    var key   = document.getElementById('key');

    if(key.value.trim().length < 6 || key.value.trim().length > 9){
        $.toast({
            heading: 'Alerta',
            text: 'Código inválido!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        key.focus();
        permitir = 0;
        return;
    }

    $.ajax({
        url  : "app/api/login",
        type : 'post',
        data : {
        recoverkey : true,
        header     : 'application/json',
        email      : email.value.trim(),
        cashe      : key.value.trim()
    },
    dataType: 'json',
    beforeSend : function(){
        load();
    }})
    .done(function(msg){
        
        if (Array.isArray(msg)) {
            location.href = './recover?user='+msg[0]['user']+'&key='+msg[0]['key'];
            return;
        } else {
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

//Altera senha
if (document.querySelector('#entrar')) {
    document.querySelector('#entrar').addEventListener('click',function(){
        entrar();    
    });    
}

function entrar() {

    if(permitir == 0){
        permitir = 1;
    }else{
        return;
    }

    var email   = document.getElementById('email');
    var key     = document.getElementById('key');
    var senha   = document.getElementById('senha');
    var confirm = document.getElementById('senha-confirm');
    var check   = document.getElementById('sessionCashe');
    
    if(senha.value.trim().length < 6){
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
    }else if(senha.value.trim() != confirm.value.trim()){
        $.toast({
            heading: 'Alerta',
            text: 'As senhas não combinam!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        confirm.focus();
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
        recoverpass : true,
        header      : 'application/json',
        email       : email.value.trim(),
        cashe       : key.value.trim(),
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
                text: 'A sua senha foi alterada com sucesso!',
                icon: 'info',
                loader: true,
                loaderBg: '#0088bd'
            });
            setTimeout(function(){ location.reload(); }, 3000);
            return;
        } else {
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