if(document.getElementById('newsletter-add')){
    document.getElementById('newsletter-add').addEventListener('click',function() {
        var email    = document.querySelector('#newsletter-email');

        if (email.value.trim().length < 6 || email.value.trim().search('@') == -1 || email.value.trim().match(/\s/g) != null) {
            $.toast({
                heading: 'Alerta',
                text: 'Insira um email valido!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
            email.focus();
            return;
        }

        $.ajax({
            url  : "app/api/newsletter/newsletter",
            type : 'post',
            data : {
            add_newsletter : true,
            email          : email.value.trim(),
        },
        dataType: 'json',
        beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if(msg == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível adicionar o seu email a NewsLetter!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }else if(msg == 1){
                $.toast({
                    heading: 'Alerta',
                    text: 'O seu email foi adicionado a NewsLetter!',
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
                email.value = '';
            }else{
                $.toast({
                    heading: 'Alerta',
                    text: msg,
                    icon: 'info',
                    loader: true,
                    loaderBg: '#0088bd'
                });
            }
            onload();   
        })
        .fail(function(jqXHR, textStatus, msg){
            onload();
            $.toast({
                heading: 'Alerta',
                text: 'Serviço indisponível tente novamente mais tarde!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
            });
        });
    });
}