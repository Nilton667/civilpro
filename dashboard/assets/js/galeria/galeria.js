document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;
  
    //Adicionar localizacao
    document.getElementById('add-galeria-localizacao')?.addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }

      var localizacao = document.querySelector('#galeria-localizacao-nome');
  
      if (localizacao.value.trim() == '') {
        $.toast({
        heading: 'Alerta',
        text: 'Insira uma localização valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
        localizacao.focus();
        permitir = 0;
        return;
      }
  
      $.ajax({
      url  : "app/api/galeria/galeria",
      type : 'post',
      data : {
      add_localizacao : true,
      header          : 'application/json',
      localizacao     : localizacao.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
      load();
      }})
      .done(function(msg){
      if(msg == 1){
          location.href = './?galeria';
          return;
      }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a nova localização!',
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

    //Selecionar loocalizações
    $(document).delegate('#localizacao-id', 'click', function() {
      if (this.checked) {
      document.querySelectorAll('table input[type="checkbox"]').forEach(function(element, index){
        element.checked = true;
      });
      }else{
      document.querySelectorAll('table input[type="checkbox"]').forEach(function(element, index){
        element.checked = false;
      });
      }
    });

    //Deletar localizações
    if (document.querySelector('#modal-delete-localizacao')){
      document.querySelector('#modal-delete-localizacao').addEventListener('click', function(){

      _seleted = 0;
      document.querySelectorAll('.table input[localizacao-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted++;
        }
      });

      if(_seleted > 0){
        document.querySelector('#remove-content-localizacao').innerHTML = _seleted+' localização selecionada(s) pretende mesmo removela(s)?';
        document.getElementById('remove-item-localizacao').hidden       = false;
      }else{
        document.querySelector('#remove-content-localizacao').innerHTML = 'Nenhuma localização selecionada!';
        document.getElementById('remove-item-localizacao').hidden       = true;
      }

      $('#modal-remove-localizacao').modal('show');

      });
    }

    //Evento Deletar localização
    document.getElementById('remove-item-localizacao').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }

      _seleted = '';
      document.querySelectorAll('.table input[localizacao-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted += element.getAttribute('localizacao-select')+',';
        }
      });

      $.ajax({
        url  : "app/api/galeria/galeria",
        type : 'post',
        data : {
        remove_localizacao : true,
        header             : 'application/json',
        localizacao_id     : _seleted,
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
          location.reload();
          return;
        }else {
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível efectuar o seu pedido!',
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

    //Editar localização
    $(document).delegate('#localizacao-edit', 'click', function(e) {
      document.querySelector('#edit-localizacao-id').value   = $(this).attr('data-id');
      document.querySelector('#edit-localizacao-nome').value = $(this).attr('data-nome');
      $('#modal-edit-localizacao').modal('show');
    });

    //Evento Editar localizacao
    document.getElementById('edit-localizacao').addEventListener('click',function(){
      
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var localizacao    = document.querySelector('#edit-localizacao-nome');
      var localizacao_id = document.querySelector('#edit-localizacao-id');
  
      if (localizacao.value.trim() == '') {
        $.toast({
          heading: 'Alerta',
          text: 'Insira uma localização valida!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        localizacao.focus();
        permitir = 0;
        return;
      }
      
      $.ajax({
        url  : "app/api/galeria/galeria",
        type : 'post',
        data : {
        edit_localizacao : true,
        header           : 'application/json',
        localizacao      : localizacao.value.trim(),
        localizacao_id   : localizacao_id.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
            location.reload();
            return;
        }else if(msg == 0){
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível editar a sua localização!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
          });
        } else {
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

    //Adicionar galeria
    document.getElementById('add-galeria').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var pasta = document.querySelector('#galeria-nome');
  
      if (pasta.value.trim() == '') {
          $.toast({
          heading: 'Alerta',
          text: 'Insira um nome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
          });
          pasta.focus();
      permitir = 0;
      return;
      }
  
      $.ajax({
      url  : "app/api/galeria/galeria",
      type : 'post',
      data : {
      add_galeria : true,
      header        : 'application/json',
      galeria       : pasta.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
      load();
      }})
      .done(function(msg){
      if(msg == 1){
          location.href = './?galeria';
          return;
      }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a sua galeria!',
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
  
    //Deletar
    var _seleted = 0;
    if (document.querySelector('#modal-delete')){
      $('body').delegate('#modal-delete', 'click', function(){
  
        _seleted = this.getAttribute('data-id');
        document.querySelector('#remove-content').innerHTML = 'Pretende mesmo remover esta pasta de imagens?';
        document.getElementById('remove-item').hidden       = false;
  
        $('#modal-remove-item').modal('show');
  
      });
    }
  
    //Evento Deletar
    document.getElementById('remove-item').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
    
      $.ajax({
        url  : "app/api/galeria/galeria",
        type : 'post',
        data : {
        remove_galeria : true,
        header         : 'application/json',
        galeria_id     : _seleted,
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
          location.reload();
          return;
        }else {
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível efectuar o seu pedido!',
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
  
    //Editar galeria
    $(document).delegate('#modal-edit', 'click', function(e) {
      document.querySelector('#edit-galeria-id').value   = $(this).attr('data-id');
      document.querySelector('#edit-galeria-nome').value = $(this).attr('data-galeria');
      $('#modal-edit-item').modal('show');
    });
  
    //Evento Editar
    document.getElementById('edit-galeria').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var galeria    = document.querySelector('#edit-galeria-nome');
      var galeria_id = document.querySelector('#edit-galeria-id');
  
      if (galeria.value.trim() == '') {
        $.toast({
          heading: 'Alerta',
          text: 'Insira uma galeria valida!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        galeria.focus();
        permitir = 0;
        return;
      }
      
      $.ajax({
        url  : "app/api/galeria/galeria",
        type : 'post',
        data : {
        edit_galeria : true,
        header       : 'application/json',
        galeria      : galeria.value.trim(),
        galeria_id   : galeria_id.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
            location.reload();
            return;
        }else if(msg == 0){
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível editar a sua galeria!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
          });
        } else {
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

    //Confirmar imagem na galeria
    if(document.getElementById('add-image')){
        $('body').delegate('#add-image', 'change', function name(params) {
            if(this.value != ''){
                document.getElementById('id-form').value = this.getAttribute('id-form');
                document.getElementById('select-localizacao_id').value = 0;
                $('#add-image-modal').modal('show');
            } 
        })
    }

    //Adicionar imagem
    $('.add-image').click(function(){

        var id          = document.getElementById('id-form').value;
        var localizacao = document.getElementById('select-localizacao_id'); 

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }

        if(localizacao.value.trim() == 0 || localizacao.value.trim() == ''){
          $.toast({
            heading: 'Alerta',
            text: 'Selecione uma localização!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
          });
          localizacao.focus();
          permitir = 0;
          return;
        }

        $('#galeria-add-image-'+id).ajaxForm({
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
        url : 'app/api/galeria/galeria',
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
            url  : "app/api/galeria/galeria",
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
                    text: msg,//'Não foi possível remover a imagem!',
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

});