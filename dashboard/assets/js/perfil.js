document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;

    if(document.querySelector('#perfil-save')){

        let nome          = document.querySelector('#perfil-edit-nome');
        let sobrenome     = document.querySelector('#perfil-edit-sobrenome');
        let identificacao = document.querySelector('#perfil-edit-identificacao');
        let nacionalidade = document.querySelector('#perfil-edit-nacionalidade');
        let morada        = document.querySelector('#perfil-edit-morada');
        let genero        = document.querySelector('#perfil-edit-genero');
        let telemovel     = document.querySelector('#perfil-edit-telemovel');

        document.querySelector('#perfil-save').addEventListener('click',function(){

            if(permitir == 0){
                permitir = 1;
            }else{
                return;
            }

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
            }else if(nacionalidade.value.trim() == ""){
                $.toast({
                    heading: 'Alerta',
                    text: 'Selecione a sua nacionalidade!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                permitir = 0;
                nacionalidade.focus();
                return;
            }else if(genero.value.trim() == ""){
                $.toast({
                    heading: 'Alerta',
                    text: 'Selecione o seu genero!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                permitir = 0;
                genero.focus();
                return;
            }

            $.ajax({
                url  : "app/api/perfil",
                type : 'post',
                data : {
                edit_adm      : true,
                header        : 'application/json',
                nome          : nome.value.trim(),
                sobrenome     : sobrenome.value.trim(),
                identificacao : identificacao.value.trim(),
                nacionalidade : nacionalidade.value.trim(),
                morada        : morada.value.trim(),
                genero        : genero.value.trim(),
                telemovel     : telemovel.value.trim(),
            },
            dataType: 'json',
            beforeSend : function(){
                load();
            }})
            .done(function(msg){
                if (msg == 1) {
                    location.href = './?perfil';
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

        });
    }

    //Altera foto de perfil
    $('#upload-image-progress').hide();
    $('#photo_form #upload').click(function(){
        
        $('#photo_form #upload').attr('disabled','true');
        $('#photo_form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
            $('#upload-image-progress').show();
            $('#upload-image-progress > div').css({
                width: percentComplete+'%',
            });
        },
        success: function(data){

            if(data['status'] && data['status'] == 1){
                $.toast({
                    heading: 'Alerta',
                    text: 'A sua foto de perfil foi alterada com sucesso!',
                    showHideTransition: 'fade',
                    icon: 'info',
                    loader: true,
                });
                document.querySelectorAll('#userData-image').forEach(function(element, index) {
                    element.src = 'assets/img/perfil/'+data['imagem'];
                });
                $('#photo-modal').modal('hide');
            }else {
                $.toast({
                    heading: 'Alerta',
                    text: data,
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }

            $('#upload-image-progress').hide();
            $('#upload-image-progress > div').css({
                width: '0%',
            });
            $('#photo_form #upload').removeAttr('disabled');
            fileName('Escolher imagem');

        },
        error: function(er){
            $.toast({
                heading: 'Alerta',
                text: er,
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            $('#upload-image-progress').hide();
            $('#upload-image-progress > div').css({
                width: '0%',
            });
            $('#photo_form #upload').removeAttr('disabled');
            fileName('Escolher imagem');
        },
        dataType: 'json',
        url : 'app/api/perfil',
        resetForm: true,
        }).submit();
    });

    //Nova senha
    if(document.querySelector('#update-confirm')){
        document.querySelector('#update-confirm').addEventListener('click', function () {

            if(permitir == 0){
                permitir = 1;
            }else{
                return;
            }

            var email         = document.querySelector('#perfil-edit-email');
            var senha_atual   = document.querySelector('#update-password-atual');
            var senha_new     = document.querySelector('#update-password-new');
            var senha_confirm = document.querySelector('#update-password-confirm');

            if(senha_atual.value.trim() == ''){
                $.toast({
                    heading: 'Alerta',
                    text: 'Insira a sua senha atual!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                senha_atual.focus();
                permitir = 0;
                return;
            }else if(senha_new.value.trim().length < 6){
                $.toast({
                    heading: 'Alerta',
                    text: 'A senha deve ter no minímo 6 caracteres!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                senha_new.focus();
                permitir = 0;
                return;
            }else if(senha_confirm.value.trim() != senha_new.value.trim()){
                $.toast({
                    heading: 'Alerta',
                    text: 'As senhas não combinam!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                senha_confirm.focus();
                permitir = 0;
                return;
            }
        
            $.ajax({
                url  : "app/api/perfil",
                type : 'post',
                data : {
                update_password : true,
                header          : 'application/json',
                senha           : senha_atual.value.trim(),
                email           : email.value.trim(),
                senha_new       : senha_new.value.trim(),
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
                        showHideTransition: 'fade',
                        icon: 'info',
                        loader: true,
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
                $.toast({
                    heading: 'Alerta',
                    text: msg,
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                permitir = 0;
                onload();
            });

        });
    }

});

function fileName(str){
    if(document.querySelector('#input-file-label')){
        document.querySelector('#input-file-label').textContent = str.trim();
    }
}
