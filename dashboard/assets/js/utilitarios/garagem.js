document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;
  
    //Adicionar garagem
    document.getElementById('add-garagem').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var garagem = document.querySelector('#garagem-nome');
  
      if (garagem.value.trim() == '') {
          $.toast({
          heading: 'Alerta',
          text: 'Insira um nome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
          });
      garagem.focus();
      permitir = 0;
      return;
      }
  
      $.ajax({
      url  : "app/api/utilitarios/garagem",
      type : 'post',
      data : {
      add_garagem : true,
      header        : 'application/json',
      garagem     : garagem.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
      load();
      }})
      .done(function(msg){
      if(msg == 1){
          location.href = './?garagem';
          return;
      }else if(msg == 0){
          $.toast({
          heading: 'Alerta',
          text: 'Não foi possível registar a sua garagem!',
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
  
    //Selecionar itens
    $(document).delegate('#garagem-id', 'click', function() {
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
  
    //Deletar
    if (document.querySelector('#modal-delete')){
      document.querySelector('#modal-delete').addEventListener('click', function(){
  
      _seleted = 0;
      document.querySelectorAll('.table input[garagem-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted++;
        }
      });
  
      if(_seleted > 0){
        document.querySelector('#remove-content').innerHTML = _seleted+' garage(m)(ns) selecionada(s) pretende mesmo removela(s)?';
        document.getElementById('remove-item').hidden       = false;
      }else{
        document.querySelector('#remove-content').innerHTML = 'Nenhum garagem selecionada!';
        document.getElementById('remove-item').hidden       = true;
      }
  
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
  
      _seleted = '';
      document.querySelectorAll('.table input[garagem-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted += element.getAttribute('garagem-select')+',';
        }
      });
    
      $.ajax({
        url  : "app/api/utilitarios/garagem",
        type : 'post',
        data : {
        remove_garagem : true,
        header           : 'application/json',
        garagem_id     : _seleted,
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
  
    //Editar garagem
    $(document).delegate('#modal-edit', 'click', function(e) {
      document.querySelector('#edit-garagem-id').value   = $(this).attr('data-id');
      document.querySelector('#edit-garagem-nome').value = $(this).attr('data-garagem');
      $('#modal-edit-item').modal('show');
    });
  
    //Evento Editar
    document.getElementById('edit-garagem').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var garagem    = document.querySelector('#edit-garagem-nome');
      var garagem_id = document.querySelector('#edit-garagem-id');
  
      if (garagem.value.trim() == '') {
        $.toast({
          heading: 'Alerta',
          text: 'Insira um nome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        garagem.focus();
        permitir = 0;
        return;
      }
      
      $.ajax({
        url  : "app/api/utilitarios/garagem",
        type : 'post',
        data : {
        edit_garagem : true,
        header         : 'application/json',
        garagem      : garagem.value.trim(),
        old_garagem  : document.querySelector('#garagem-nome-'+garagem_id.value.trim()).textContent,
        garagem_id   : garagem_id.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
            $('#modal-edit-item').modal('hide');
            if (document.querySelector('#garagem-nome-'+garagem_id.value.trim())) {
                $('#garagem-nome-'+garagem_id.value.trim()).html(garagem.value.trim());
            }
            if (document.querySelector('.modal-edit-'+garagem_id.value.trim())) {
                $('.modal-edit-'+garagem_id.value.trim()).attr('data-garagem', garagem.value.trim());
            }
        }else if(msg == 0){
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível editar a sua garagem!',
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
  
  });