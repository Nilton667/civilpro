document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;

    //Preferência do sistema

    if(document.querySelector('#systemSave')){

    //Setar definições do sistema
    document.getElementById('systemSave').addEventListener('click',function(){

        if(permitir == 0){
          permitir = 1;
        }else{
          return;
        }
        
        var systemNome  = document.getElementById('system-nome');
        var systemFont  = document.getElementById('system-font');
        var systemLang  = document.getElementById('system-lang');
        var systemHttp  = document.getElementById('system-http');
        var systemTheme = document.getElementById('system-theme');
        var systemImage = document.getElementById('system-image');

        if(systemHttp.checked){
            systemHttp = 1;
        }else{
            systemHttp = 0;
        }
    
        $.ajax({
            url  : "app/api/prefs",
            type : 'post',
            data : {
            setSystemPrefs : true,
            header      : 'application/json',
            systemNome  : systemNome.value.trim(),
            systemFont  : systemFont.value.trim(),
            systemLang  : systemLang.value.trim(),
            systemTheme : systemTheme.value.trim(),
            systemImage : systemImage.value.trim(),
            systemHttp  : systemHttp,
        },
        dataType: 'json',
        beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if(msg == 1){
                $.toast({
                    heading: 'Alerta',
                    text: 'As suas preferências do sistema foram salvas com sucesso!',
                    showHideTransition: 'fade',
                    icon: 'info',
                    loader: true,
                });
                setTimeout(function(){ location.reload(); }, 3000);
                return;
            }else if(msg == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível salvar as suas preferências do sistema!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }else {
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

    if(document.querySelector('#systemReset')){

    //Resetar definições do sistema
    document.getElementById('systemReset').addEventListener('click',function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }
    
        $.ajax({
            url  : "app/api/prefs",
            type : 'post',
            data : {
            setSystemPrefs : true,
            header     : 'application/json',
        },
        dataType: 'json',
        beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if(msg == 1){
                $.toast({
                    heading: 'Alerta',
                    text: 'As suas preferências do sistema foram resetadas com sucesso!',
                    showHideTransition: 'fade',
                    icon: 'info',
                    loader: true,
                });
                setTimeout(function(){ location.reload(); }, 3000);
                return;
            }else if(msg == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível resetar as suas preferências do sistema!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }else {
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

    //Envio de email
    if(document.querySelector('#email-save')){

    //Setar definições do sistema
    document.getElementById('email-save').addEventListener('click',function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }
        
        var hostName     = document.getElementById('mail-hostName');
        var hostEmail    = document.getElementById('mail-hostEmail');
        var hostPort     = document.getElementById('mail-hostPort');
        var hostPassword = document.getElementById('mail-hostPassword');
    
        //Emissão / recepcão
        var emissorName   = document.getElementById('mail-emissorName');
        var emissorEmail  = document.getElementById('mail-emissorEmail');
        var receptorName  = document.getElementById('mail-receptorName');
        var receptorEmail = document.getElementById('mail-receptorEmail');
        
        $.ajax({
            url  : "app/api/prefs",
            type : 'post',
            data : {
            setEmailPrefs : true,
            header       : 'application/json',
            hostName     : hostName.value.trim(),
            hostEmail    : hostEmail.value.trim(),
            hostPort     : hostPort.value.trim(),
            hostPassword : hostPassword.value.trim(),
            ///////
            emissorName    : emissorName.value.trim(),
            emissorEmail   : emissorEmail.value.trim(),
            receptorName   : receptorName.value.trim(),
            receptorEmail  : receptorEmail.value.trim(),
        },
        dataType: 'json',
        beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if(msg == 1){
                $.toast({
                    heading: 'Alerta',
                    text: 'As suas preferências de email foram salvas com sucesso!',
                    showHideTransition: 'fade',
                    icon: 'info',
                    loader: true,
                });
                setTimeout(function(){ location.reload(); }, 3000);
                return;
            }else if(msg == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível salvar as suas preferências de email!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }else {
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
        
    //testar definições de email
    document.querySelector('#email-test').addEventListener('click',function(){

        var hostName     = document.getElementById('mail-hostName');
        var hostEmail    = document.getElementById('mail-hostEmail');
        var hostPort     = document.getElementById('mail-hostPort');
        var hostPassword = document.getElementById('mail-hostPassword');
        var content      = document.querySelector('#mail-status-area');

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        $.ajax({
            url  : "app/api/prefs",
            type : 'post',
            data : {
            test_email     : true,
            header         : 'application/json',
            hostName       : hostName.value.trim(),
            hostEmail      : hostEmail.value.trim(),
            hostPort       : hostPort.value.trim(),
            hostPassword   : hostPassword.value.trim(),
        },
        dataType: 'json',
        beforeSend : function(){
            load();
        }})
        .done(function(msg){
            
            if (msg == 1) {
                let data = '{\nHost: '+hostName.value.trim()+',\nUsername: '+hostEmail.value.trim()+',\nPort: '+hostPort.value.trim()+'\n}';
                content.value = data;
                content.focus();
            }else {
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível estabelecer uma conexão com o seu servidor smtp!',
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

    if(document.querySelector('#emailReset')){

        //Resetar definições de email
        document.getElementById('emailReset').addEventListener('click',function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }
    
        $.ajax({
            url  : "app/api/prefs",
            type : 'post',
            data : {
            setEmailPrefs : true,
            header         : 'application/json',
        },
        dataType: 'json',
        beforeSend : function(){
            load();
        }})
        .done(function(msg){
            if(msg == 1){
                $.toast({
                    heading: 'Alerta',
                    text: 'As suas definições de email foram resetadas com sucesso!',
                    showHideTransition: 'fade',
                    icon: 'info',
                    loader: true,
                });
                setTimeout(function(){ location.reload(); }, 3000);
                return;
            }else if(msg == 0){
                $.toast({
                    heading: 'Alerta',
                    text: 'Não foi possível resetar as suas definições de email!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                });
            }else {
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