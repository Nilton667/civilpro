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
                    toastr["error"]('O campo nome não pode estar vazio!');
                    comment_nome.focus();
                    return;
                }
                comment_nome = comment_nome.value.trim();
            }
            
            if(data.value.trim() == ''){
                toastr["error"]('O campo comentario não pode estar vazio!');
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
                    toastr["error"](msg);
                }
                onload();
            })
            .fail(function(jqXHR, textStatus, msg){
                toastr["error"](msg);
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
                toastr["error"](msg);
            }
            onload();
        })
        .fail(function(jqXHR, textStatus, msg){
            toastr["error"](msg);
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
                toastr["error"](msg);
            }
            onload();
        })
        .fail(function(jqXHR, textStatus, msg){
            toastr["error"](msg);
            onload();
        });
    });

    //Editar comentarios e subcomentarios
    $(document).delegate('.comment-edit', 'click', function(){
        var id           = document.querySelector('#edit-comment-id');
        var text         = document.querySelector('#edit-comment-txt');
        var target       = document.querySelector('#edit-comment-target');

        id.value         = this.getAttribute('data-id');
        target.value     = this.getAttribute('data-target');
        text.textContent = this.getAttribute('data-comentario');

        $('#edit-comment-modal').modal('show');
        text.focus();
    });
    $(document).delegate('.sub-comment-edit', 'click', function(){
        var id           = document.querySelector('#edit-comment-id');
        var text         = document.querySelector('#edit-comment-txt');
        var target       = document.querySelector('#edit-comment-target');

        id.value         = this.getAttribute('data-id');
        target.value     = this.getAttribute('data-target');
        text.textContent = this.getAttribute('data-comentario');
        
        $('#edit-comment-modal').modal('show');
        text.focus();
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
        toastr["error"]('Insira uma resposta valida!');
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
            toastr["error"](msg);
        }
        onload();
    })
    .fail(function(jqXHR, textStatus, msg){
        toastr["error"](msg);
        onload();
    });
}

function comentarioEdit(){
    var id           = document.querySelector('#edit-comment-id');
    var text         = document.querySelector('#edit-comment-txt');
    var target       = document.querySelector('#edit-comment-target');

    if(text.value.trim() == ''){
        toastr["error"]('O campo comentario não pode estar vazio!');
        text.focus();
        return;
    }

    $.ajax({
        url  : "app/api/feed/comentario",
        type : 'post',
        data : {
        edit_comment   : target.value.trim(),
        header         : 'application/json',
        comentario_id  : id.value.trim(),
        comentario     : text.value.trim(),
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
            toastr["error"]('Não foi possível editar o seu comentario!');
        }
        onload();
    })
    .fail(function(jqXHR, textStatus, msg){
        toastr["error"](msg);
        onload();
    });
}