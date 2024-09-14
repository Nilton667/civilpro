document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;

    CKEDITOR.replace( 'artigo-text' );

    //Reset artigo
    if(document.querySelector('#reset-artigo')){
        document.querySelector('#reset-artigo').addEventListener('click',function(){
            CKEDITOR.instances['artigo-text'].setData();
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
                $('#image-test').modal('show');
            }
            fileReader.readAsDataURL(file);

        }); 
    }

    //Regista artigo
    $('#news-artigo').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        var nome        = document.querySelector('#nome');
        var descricao   = document.querySelector('#descricao');
        var quantidade  = document.querySelector('#quantidade');
        var preco       = document.querySelector('#preco');
        var cor         = document.querySelector('#cor');
        var tamanho    = document.querySelector('#tamanho');

        cor.value = '';
        $('.cores > div').each(function() {
            if($(this).attr('cor').trim() != ''){
                cor.value  += $(this).attr('cor')+',';
            }
        })
    
        tamanho.value = '';
        $('.tamanhos > div').each(function() {
            if($(this).attr('tamanho').trim() != ''){
                tamanho.value  += $(this).attr('tamanho')+',';
            }
        })

        descricao.value = CKEDITOR.instances['artigo-text'].getData();

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
        }else if(quantidade.value.trim() == ''){
            $.toast({
                heading: 'Alerta',
                text: 'Insira uma quantidade valida!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            quantidade.focus();
            permitir = 0;
            return;
        }else if(preco.value.trim() == ''){
            $.toast({
                heading: 'Alerta',
                text: 'Insira um preço valido!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            preco.focus();
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
            CKEDITOR.instances['artigo-text'].focus();
            permitir = 0;
            return;
        }

        $('#blog-form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
            $('#progress').modal('show');
            with(document.querySelector('#progress .progress-bar').style){
                width = percentComplete+'%';
            }
        },
        success: function(data){
            if (data == 1) {
                $.toast({
                    heading: 'Alerta',
                    text: 'O seu artigo foi registado com sucesso!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                setTimeout(function(){ location.href= './?artigos'; }, 3000);
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível registar o seu artigo!',
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
            setTimeout(function(){
                $('#progress').modal('hide');
            }, 1000);
            with(document.querySelector('#progress .progress-bar').style){
                width = '0%';
            }
            permitir = 0;
        },
        error: function(err){
            $.toast({
                heading: 'Alerta',
                text: err,//'Ocorreu um problema de rede tente novamente mais tarde!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            setTimeout(function(){
                $('#progress').modal('hide');
            }, 1000);
            with(document.querySelector('#progress .progress-bar').style){
                width = '0%';
            }
            permitir = 0;
        },
        dataType: 'json',
        url : 'app/api/artigo/artigo',
        resetForm: false
        }).submit();
    });

    //Editar artigo
    $('#edit-artigo').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        var nome       = document.querySelector('#nome');
        var descricao  = document.querySelector('#descricao');
        var id         = document.querySelector('#artigo_id');
        var cor        = document.querySelector('#cor');
        var tamanho    = document.querySelector('#tamanho');

        cor.value = '';
        $('.cores > div').each(function() {
            if($(this).attr('cor').trim() != ''){
                cor.value  += $(this).attr('cor')+',';
            }
        })

        tamanho.value = '';
        $('.tamanhos > div').each(function() {
            if($(this).attr('tamanho').trim() != ''){
                tamanho.value  += $(this).attr('tamanho')+',';
            }
        })

        descricao.value = CKEDITOR.instances['artigo-text'].getData();

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
            CKEDITOR.instances['artigo-text'].focus();
            permitir = 0;
            return;
        }

        $('#blog-form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
        load();
        },
        success: function(data){
            if (data == 1) {
                $.toast({
                    heading: 'Alerta',
                    text: 'O seu artigo foi editado com sucesso!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                setTimeout(function(){ location.href= './?artigos&view='+id.value.trim(); }, 3000);
                return;
            }else{
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível editar o seu artigo!',
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
        url : 'app/api/artigo/artigo',
        resetForm: false
        }).submit();
    });

    if(document.getElementById('add-image')){
        document.getElementById('add-image').addEventListener('change', function(){
            if(this.value != ''){
                $('#add-image-modal').modal('show');
            }
        });
    }

    //Adicionar imagem
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
        url : 'app/api/artigo/artigo',
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
            url  : "app/api/artigo/artigo",
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
            url  : "app/api/artigo/artigo",
            type : 'post',
            data : {
            change_image : true,
            header       : 'application/json',
            artigo_id    : document.querySelector('#artigo_id').value.trim() ?? 0,
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