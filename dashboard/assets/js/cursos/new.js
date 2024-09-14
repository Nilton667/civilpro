document.addEventListener('DOMContentLoaded', function(){   
    
    if (!Array.isArray) {
        Array.isArray = function(arg) {
          return Object.prototype.toString.call(arg) === '[object Array]';
        };
    }

    let permitir = 0;

    CKEDITOR.replace( 'post-text' );

    //Reset form
    if(document.querySelector('#reset-curso')){
        document.querySelector('#reset-curso').addEventListener('click',function(){
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

    //Regista curso
    $('#news-curso').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        var titulo      = document.querySelector('#titulo');
        var descricao   = document.querySelector('#descricao');

        descricao.value = CKEDITOR.instances['post-text'].getData();

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
            CKEDITOR.instances['post-text'].focus();
            permitir = 0;
            return;
        }

        $('#curso-form').ajaxForm({
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
                    text: 'O seu curso foi registado com sucesso!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                setTimeout(function(){ location.href= './?cursos'}, 3000);
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível registar o seu curso!',
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
        url : 'app/api/curso/cursos',
        resetForm: false
        }).submit();
    });

    //Editar publicação
    $('#edit-curso').click(function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        var titulo      = document.querySelector('#titulo');
        var descricao   = document.querySelector('#descricao');

        descricao.value = CKEDITOR.instances['post-text'].getData();

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
            CKEDITOR.instances['post-text'].focus();
            permitir = 0;
            return;
        }

        $('#curso-form').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
        load();
        },
        success: function(data){
            if (data == 1) {
                $.toast({
                    heading: 'Alerta',
                    text: 'O seu curso foi editado com sucesso!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                setTimeout(function(){ location.reload(); }, 3000);
                return;
            }else if(data == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível editar o seu curso!',
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
        url : 'app/api/curso/cursos',
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

    //Editar video
    $('body').delegate('.open-modal-edit', 'click', function(){
        document.querySelector('#edit-id').value     = this.getAttribute('data-id');
        document.querySelector('#edit-titulo').value = this.getAttribute('data-title');
        $('#curso-modal-edit').modal('show');
    });

    if(document.getElementById('modal-edit-curso')){
        document.getElementById('modal-edit-curso').addEventListener('click', function(){

            if(permitir == 0){
                permitir = 1;
            }else{
                return;
            }

            var file = document.querySelector('#edit-titulo');

            if(file.value.trim() == ''){
                $.toast({
                    heading: 'Alerta',
                    text: 'Insira um título valido!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                permitir = 0;
                return;
            }

            load();
            $('#form-edit-curso').ajaxForm({
            uploadProgress: function (event, position, total, percentComplete) {},
            success: function(data){
                if (data == 1){
                    location.reload();
                    return;
                }else if(data == 0){
                    $.toast({
                        heading: 'Alerta',
                        text: 'Não foi possível editar o curso!',
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
                onload();
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
                onload();
            },
            dataType: 'json',
            url : 'app/api/curso/cursos',
            resetForm: false
            }).submit();

        });
    }

    //Adicionar arquivos
    if(document.getElementById('add-video')){
        $('.add-video-upload').hide();
        document.getElementById('add-video').addEventListener('change', function(){
            if(this.value != ''){
                document.querySelector('#file-name').value = this.value;
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

        var file = document.querySelector('#file-titulo');

        if(file.value.trim() == ''){
            $.toast({
                heading: 'Alerta',
                text: 'Insira um título valido!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            permitir = 0;
            return;
        }

        document.querySelector('#cursos_titulo').value = file.value.trim();

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
        url : 'app/api/curso/cursos',
        resetForm: false
        }).submit();
    });

    //Remover video
    $('body').delegate('.artigo-remove-image', 'click', function(){

        var confirm = window.confirm('Pretende mesmo remover este vídeo?');

        if(!confirm){
            return;
        }

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        $.ajax({
            url  : "app/api/curso/cursos",
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

});

//fileName
function fileName(str){
    if(document.querySelector('#input-file-label')){
        document.querySelector('#input-file-label').textContent = str.trim();
    }
}