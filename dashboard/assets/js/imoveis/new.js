if (!Array.isArray) {
    Array.isArray = function(arg) {
      return Object.prototype.toString.call(arg) === '[object Array]';
    };
} 

document.addEventListener('DOMContentLoaded', function(){   

    let permitir = 0;
    var pattern  = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    var youtReg  = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;

    CKEDITOR.replace( 'imoveis-text' );

    //Reset imoveis
    if(document.querySelector('#reset-imoveis')){
        document.querySelector('#reset-imoveis').addEventListener('click',function(){
            CKEDITOR.instances['imoveis-text'].setData();
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

    //Testar videos do youtube
    document.querySelector('#video-test').addEventListener('click', function(){
        var youtube = document.querySelector('#youtube');

        if(youtube.value.trim() != ''){
            if (!pattern.test(youtube.value.trim())) {
                $.toast({
                    heading: 'Alerta',
                    text: 'Insira um link valido!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                youtube.focus();
                return;
            }
        }else if(youtube.value.trim() == ''){
            $.toast({
                heading: 'Alerta',
                text: 'Insira um link valido!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            youtube.focus();
            return;   	
        }

        var match = youtube.value.trim().match(youtReg);
        if (match && match[2].length == 11){
            if(document.querySelector('#youtube-frame').src != 'https://www.youtube.com/embed/'+match[2]){
                document.querySelector('#youtube-frame').src = 'https://www.youtube.com/embed/'+match[2];
            }
            $('#youtube-test').modal('show');	  
        } else {
            $.toast({
                heading: 'Alerta',
                text: 'Não foi possível verificar o seu video!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
        }

    });

    //Regista imovel
    $('#news-imoveis').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        var titulo      = document.querySelector('#titulo');
        var descricao   = document.querySelector('#descricao');
        var youtube     = document.querySelector('#youtube');

        descricao.value = CKEDITOR.instances['imoveis-text'].getData();

        if(titulo.value.trim() == ''){
            $.toast({
                heading: 'Alerta',
                text: 'Insira um título valido!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            titulo.focus();
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
            CKEDITOR.instances['imoveis-text'].focus();
            permitir = 0;
            return;
        }else if(youtube.value.trim() != ''){
            if (!pattern.test(youtube.value.trim())) {
                $.toast({
                    heading: 'Alerta',
                    text: 'Insira um link valido!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                youtube.focus();
                permitir = 0;
                return;
            }

            var match = youtube.value.trim().match(youtReg);
            if (match && match[2].length == 11) {
                youtube.value = 'https://www.youtube.com/embed/'+match[2];  
            }
            
        }

        $('#imoveis-form').ajaxForm({
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
                    text: 'O seu imóvel foi registado com sucesso!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                setTimeout(function(){ location.href= './?imoveis'; }, 3000);
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível registar o seu imóvel!',
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
        url : 'app/api/imoveis/imoveis',
        resetForm: false
        }).submit();
    });

    //Editar publicação
    $('#edit-imoveis').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        var titulo      = document.querySelector('#titulo');
        var descricao   = document.querySelector('#descricao');
        var youtube     = document.querySelector('#youtube');

        descricao.value = CKEDITOR.instances['imoveis-text'].getData();

        if(titulo.value.trim() == ''){
            $.toast({
                heading: 'Alerta',
                text: 'Insira um título valido!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            titulo.focus();
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
            CKEDITOR.instances['imoveis-text'].focus();
            permitir = 0;
            return;
        }else if(youtube.value.trim() != ''){
            if (!pattern.test(youtube.value.trim())) {
                $.toast({
                    heading: 'Alerta',
                    text: 'Insira um link valido!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                youtube.focus();
                permitir = 0;
                return;
            }

            var match = youtube.value.trim().match(youtReg);
            if (match && match[2].length == 11) {
                youtube.value = 'https://www.youtube.com/embed/'+match[2];  
            }
            
        }

        $('#imoveis-form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
        load();
        },
        success: function(data){
            if (data == 1) {
                $.toast({
                    heading: 'Alerta',
                    text: 'O seu imóvel foi editado com sucesso!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                setTimeout(function(){ location.reload(); }, 3000);
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível editar o seu imóvel!',
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
        url : 'app/api/imoveis/imoveis',
        resetForm: false
        }).submit();
    });

    //Subcategoria
    if(document.querySelector('#categoria')){

        document.getElementById('categoria').addEventListener('change', function(){

        $.ajax({
            url  : "app/api/request",
            type : 'post',
            data : {
            subcategoria : true,
            header       : 'application/json',
            filter       : this.value.trim(),
            },
            dataType: 'json',
            beforeSend : function(){
            load();
            }})
            .done(function(msg){
            if(Array.isArray(msg)){
                item = '';
                msg.forEach(element => {
                    item += '<option value="'+element['subcategoria'].trim()+'"</option>'+element['subcategoria'].trim()+'</option>';
                });
                var sub = '<label>Subcategoria</label><select class="custom-select" id="subcategoria" name="subcategoria"> <option value="">-- Selecione uma subcategoria --</option>'+item+'</select>';
                document.getElementById('subcategoria').removeAttribute('disabled');

                document.querySelector('.sub-update').innerHTML = sub;

            }else{
                document.getElementById('subcategoria').value = '';
                document.getElementById('subcategoria').setAttribute('disabled', true);
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
            document.getElementById('subcategoria').value = '';
            document.getElementById('subcategoria').setAttribute('disabled', true);
            permitir = 0;
            onload();
            });

        });
    }

    //Adicionar video
    if(document.getElementById('add-video')){
        $('.add-video-upload').hide();
        document.getElementById('add-video').addEventListener('change', function(){
            if(this.value != ''){
                $('#add-video-modal').modal('show');
            }
        });
    }
    $('.add-video').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        $('#video-form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
            $('.add-video-comfirm').hide();
            $('.add-video-upload').show();
            with(document.querySelector('#add-video-modal .progress-bar').style){
                width = percentComplete+'%';
            }
        },
        success: function(data){
            if (data == 1){
                location.reload();
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível adicionar o vídeo!',
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
            $('#add-video-modal').modal('hide');
            permitir = 0;
            with(document.querySelector('#add-video-modal .progress-bar').style){
                width = '0%';
            }
            $('.add-video-comfirm').show();
            $('.add-video-upload').hide();
        },
        error: function(err){
            $.toast({
                heading: 'Alerta',
                text: 'Ocorreu um problema de rede tente novamente mais tarde!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            $('#add-video-modal').modal('hide');
            permitir = 0;
            with(document.querySelector('#add-video-modal .progress-bar').style){
                width = '0%';
            }
            $('.add-video-comfirm').show();
            $('.add-video-upload').hide();
        },
        dataType: 'json',
        url : 'app/api/imoveis/imoveis',
        resetForm: false
        }).submit();
    });

    //Remover video
    $('body').delegate('.artigo-remove-image-video', 'click', function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        $.ajax({
            url  : "app/api/imoveis/imoveis",
            type : 'post',
            data : {
            delete_video : this.getAttribute('data-video').trim(),
            header       : 'application/json',
            },
            dataType: 'json',
            beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if(msg == 1){
                location.reload();
                return;
            }else{
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível remover o vídeo!',
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

    //Adicionar imagem
    if(document.getElementById('add-image')){
        document.getElementById('add-image').addEventListener('change', function(){
            if(this.value != ''){
                $('#add-image-modal').modal('show');
            }
        });
    }
    
    $('.add-image').click(function(){
    
        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }
    
        $('#image-form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
            load();
        },
        success: function(data){
            if (data == 1){
                location.reload();
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível adicionar a imagem!',
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
        url : 'app/api/imoveis/imoveis',
        resetForm: false
        }).submit();
    });

    //Remover imagem
    $('body').delegate('.artigo-remove-image', 'click', function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        $.ajax({
            url  : "app/api/imoveis/imoveis",
            type : 'post',
            data : {
            delete_image : this.getAttribute('data-image').trim(),
            header       : 'application/json',
            },
            dataType: 'json',
            beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if(msg == 1){
                location.reload();
                return;
            }else{
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível remover a imagem!',
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

	$('.image-link').magnificPopup({
		type: 'image',
		gallery:{
			enabled: true,
		},
		mainClass: 'mfp-with-zoom',
		zoom: {
		    enabled: true,
		    duration: 300,
		    easing: 'ease-in-out',
		    opener: function(openerElement) {
		      return openerElement.is('img') ? openerElement : openerElement.find('img');
		    }
		}
    });

    //Verificar alteração de imagem padrão
    if(document.querySelector('.artigo-update-image')){
        $('body').delegate('.artigo-update-image', 'click', function(){
            $('#old_image').val(this.getAttribute('data-image'));
            $('#change-init-image').modal('show');
        });
    }
    if(document.querySelector('.change-image')){
        document.querySelector('.change-image').addEventListener('click', function(){
            
            if(permitir == 0){
                permitir = 1;
            }else{
                return;
            }

            var image = document.querySelector('#old_image').value.trim();

            if(image == ''){
                $.toast({
                    heading: 'Alerta',
                    text: 'Nenhuma imagem selecionada!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                permitir = 0;
                return;
            }

            $.ajax({
            url  : "app/api/imoveis/imoveis",
            type : 'post',
            data : {
            change_image : true,
            header       : 'application/json',
            imoveis_id   : document.querySelector('#imoveis_id').value.trim() ?? 0,
            old_img      : image,
            },
            dataType: 'json',
            beforeSend : function(){
                load();
            }})
            .done(function(msg){
            if(msg == 1){
                location.reload();
                return;
            }else{
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível alterar a imagem padrão!',
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

//fileName
function fileName(str){
    if(document.querySelector('#input-file-label')){
        document.querySelector('#input-file-label').textContent = str.trim();
    }
}