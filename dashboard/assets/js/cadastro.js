//Permissão para requisição
let permitir = 0;

//Carregamento da pagina
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown',setUser);
});
function setUser(e){
    if(e.keyCode == 13){
        cadastrar();
    }
}

//cadastro
document.querySelector('#cadastrar').addEventListener('click',function(){
    cadastrar();    
});

function cadastrar() {

    if(permitir == 0){
        permitir = 1;
    }else{
        return;
    }

    var nome          = document.getElementById('nome');
    var sobrenome     = document.getElementById('sobrenome');
    var email         = document.getElementById('email');
    var nacionalidade = document.getElementById('nacionalidade');
    var genero        = document.getElementById('genero');
    var senha         = document.getElementById('senha');
    var check         = document.getElementById('sessionCashe');

    if(nome.value.trim().length < 4 || nome.value.trim().length > 9 || (/\s/g.test(nome.value.trim())) == true){
        $.toast({
            heading: 'Alerta',
            text: 'Insira um nome valido!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        nome.focus();
        permitir = 0;
        return;
    }else if(sobrenome.value.trim().length < 4 || sobrenome.value.trim().length > 9 || (/\s/g.test(sobrenome.value.trim())) == true){
        $.toast({
            heading: 'Alerta',
            text: 'Insira um sobrenome valido!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        sobrenome.focus();
        permitir = 0;
        return;
    }else if(email.value.trim().length < 6 || email.value.search('@') == -1){
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
    }else if(nacionalidade.value.trim() == ''){
        $.toast({
            heading: 'Alerta',
            text: 'Selecione a sua nacionalidade!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        nacionalidade.focus();
        permitir = 0;
        return;
    }else if(genero.value.trim() == ''){
        $.toast({
            heading: 'Alerta',
            text: 'Selecione o seu genero!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        genero.focus();
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
        cadastro      : true,
        header        : 'application/json',
        nome          : nome.value.trim(),
        sobrenome     : sobrenome.value.trim(),
        email         : email.value.trim(),
        nacionalidade : nacionalidade.value.trim(),
        genero        : genero.value.trim(),
        senha         : senha.value.trim(),
        checkbox      : check,
        dispositivo   : navigator.appName,
    },
    dataType: 'json',
    beforeSend : function(){
        load();
    }})
    .done(function(msg){
        
        if (msg == 1) {
            $.toast({
                heading: 'Alerta',
                text: 'A sua conta foi cadastrada com sucesso!',
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