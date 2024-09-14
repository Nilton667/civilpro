document.addEventListener('DOMContentLoaded', function(){

    if(document.getElementById('comment-post')){
        document.getElementById('comment-post').addEventListener('click', function(){
            
            var id           = document.getElementById('comment-id');
            var user_id      = document.getElementById('comment-user-id');
            var comment_nome = document.getElementById('comment-nome');
            var data         = document.getElementById('comment-data');

            if(user_id == null){
                user_id = 0;
            }else{
                user_id = user_id.value.trim();
            }

            if(comment_nome == null){
                comment_nome = '';
            }else{
                if(comment_nome.value.trim() == ''){
                    $.toast({
                        heading: 'Alerta',
                        text: 'O compo nome não pode estar vazio!',
                        showHideTransition: 'fade',
                        icon: 'error',
                        loader: true,
                    });
                    comment_nome.focus();
                    return;
                }
                comment_nome = comment_nome.value.trim();
            }
            
            if(data.value.trim() == ''){
                $.toast({
                    heading: 'Alerta',
                    text: 'O compo comentario não pode estar vazio!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
                data.focus();
                return;
            }

            $.ajax({
                url  : "app/api/feed/comentario",
                type : 'post',
                data : {
                comment_add  : true,
                header       : 'application/json',
                id_post      : id.value.trim(),
                user_id      : user_id,
                comment_nome : comment_nome,
                comentario   : data.value.trim(),
            },
            dataType: 'json',
            beforeSend : function(){
                load();
            }})
            .done(function(msg){
                if (msg == 1) {
                    location.reload();
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
                onload();
            });

        });
    }

    //Remover comentario
    $(document).delegate('.comment-remove', 'click', function(){

        var confirm = window.confirm('Pretende mesmo remover o seu comentario?');

        if(confirm == false){
            return;
        }

        $.ajax({
            url  : "app/api/feed/comentario",
            type : 'post',
            data : {
            comment_remove : true,
            header         : 'application/json',
            comentario_id  : this.getAttribute('data-id'),
        },
        dataType: 'json',
        beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if (msg == 1) {
                location.reload();
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
            onload();
        });
    });

    $(document).delegate('.sub-comment-add', 'click', function(){ 
        subCommentAdd(this.getAttribute('data-sub')); 
    });
    $(document).delegate('.sub-comment', 'keyup', function(e){ 
        if(e.keyCode == 13){ 
            subCommentAdd(this.getAttribute('data-sub')); 
        } 
    });

    //Remover subComentario
    $(document).delegate('.sub-comment-remove', 'click', function(){

        var confirm = window.confirm('Pretende mesmo remover o seu comentario?');

        if(confirm == false){
            return;
        }

        $.ajax({
            url  : "app/api/feed/comentario",
            type : 'post',
            data : {
            sub_comment_remove : true,
            header             : 'application/json',
            comentario_id      : this.getAttribute('data-id'),
        },
        dataType: 'json',
        beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if (msg == 1) {
                location.reload();
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
            onload();
        });
    });

});

function subCommentAdd(id_comemtario) {
    var id      = id_comemtario;
    var data    = document.querySelector('.sub-comment-'+id_comemtario);
    var user_id = document.getElementById('comment-user-id');

    if(user_id == null){
        user_id = 0;
    }else{
        user_id = user_id.value.trim();
    }

    if(data.value.trim() == ''){
        $.toast({
            heading: 'Alerta',
            text: 'Insira uma resposta valida!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        data.focus();
        return;
    }

    $.ajax({
        url  : "app/api/feed/comentario",
        type : 'post',
        data : {
        sub_comment_add: true,
        header         : 'application/json',
        user_id        : user_id,
        comentario_id  : id,
        comentario     : data.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
        load();
    }})
    .done(function(msg){
        if (msg == 1) {
            location.reload();
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
        onload();
    });
}