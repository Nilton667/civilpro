if (!Array.isArray) {
    Array.isArray = function(arg) {
      return Object.prototype.toString.call(arg) === '[object Array]';
    };
} 

document.addEventListener('DOMContentLoaded', function(){   

    let permitir = 0;

    CKEDITOR.replace( 'post-text' );

    //Reset post
    if(document.querySelector('#reset-post')){
        document.querySelector('#reset-post').addEventListener('click',function(){
            CKEDITOR.instances['post-text'].setData();
            fileName('Escolher arquivo(s)');
        });    
    }

    //Visualizar imagem
    if(document.querySelector('#visualizar-imagem')){
        document.querySelector('#visualizar-imagem').addEventListener('click', function(){

            if(document.querySelector('#image').value.trim() == ''){
                $.toast({
                    heading: 'Alerta',
                    text: 'Selecione no mínimo uma imagem para visualizar!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                return;
            }

            const file           = $('#image')[0].files[0];
            const fileReader     = new FileReader();
            fileReader.onloadend = function(){
                document.querySelector('#view-image').src = fileReader.result;
            }
            fileReader.readAsDataURL(file);
            $('#image-test').modal('show');

        }); 
    }

    //Regista publicação
    $('#news-service').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        var nome        = document.querySelector('#nome');
        var descricao   = document.querySelector('#descricao');
        descricao.value = CKEDITOR.instances['post-text'].getData();

        if(nome.value.trim() == ''){
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
        }else if(descricao.value.trim() == ''){
            $.toast({
                heading: 'Alerta',
                text: 'Insira uma descrição valida!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            CKEDITOR.instances['post-text'].focus();
            permitir = 0;
            return;
        }

        $('#service-form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
            $('#progress').modal('show');
            with(document.querySelector('#progress .progress-bar').style){
                width = percentComplete+'%';
            }
        },
        success: function(data){
            if (data == 1){
                $.toast({
                    heading: 'Alerta',
                    text: 'O seu serviço foi registado com sucesso!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                setTimeout(function(){ location.href= './?servicos_list'; }, 3000);
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível registar o seu serviço!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }else{
                $.toast({
                    heading: 'Alerta',
                    text: data,
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });  
            }
            permitir = 0;
            $('#progress').modal('hide');
            with(document.querySelector('#progress .progress-bar').style){
                width = '0%';
            }
        },
        error: function(err){
            $.toast({
                heading: 'Alerta',
                text: 'Ocorreu um problema de rede tente novamente mais tarde!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            permitir = 0;
            $('#progress').modal('hide');
            with(document.querySelector('#progress .progress-bar').style){
                width = '0%';
            }
        },
        dataType: 'json',
        url : 'app/api/servicos/servicos',
        resetForm: false
        }).submit();
    });

    //Editar publicação
    $('#edit-service').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        var nome        = document.querySelector('#nome');
        var descricao   = document.querySelector('#descricao');
        descricao.value = CKEDITOR.instances['post-text'].getData();

        if(nome.value.trim() == ''){
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
        }else if(descricao.value.trim() == ''){
            $.toast({
                heading: 'Alerta',
                text: 'Insira uma descrição valida!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            CKEDITOR.instances['post-text'].focus();
            permitir = 0;
            return;
        }

        $('#service-form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
        load();
        },
        success: function(data){
            if (data == 1) {
                $.toast({
                    heading: 'Alerta',
                    text: 'O seu serviço foi editado com sucesso!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                setTimeout(function(){ location.reload(); }, 3000);
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível editar o seu serviço!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }else{
                $.toast({
                    heading: 'Alerta',
                    text: data,
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }
            onload();
            permitir = 0;
        },
        error: function(err){
            $.toast({
                heading: 'Alerta',
                text: 'Ocorreu um problema de rede tente novamente mais tarde!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            onload();
            permitir = 0;
        },
        dataType: 'json',
        url : 'app/api/servicos/servicos',
        resetForm: false
        }).submit();
    });

});

//fileName
function fileName(str){
    if(document.querySelector('#input-file-label')){
        document.querySelector('#input-file-label').textContent = str.trim();
    }
}