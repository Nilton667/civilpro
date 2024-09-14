document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;

  //Adicionar deliver
  document.getElementById('add-deliver').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var deliver_localizacao = document.querySelector('#deliver-localizacao');
    var deliver_latitude    = document.querySelector('#deliver-latitude');
    var deliver_longitude   = document.querySelector('#deliver-longitude');
    var deliver_preco       = document.querySelector('#deliver-preco');

    if (deliver_localizacao.value.trim() == '') {
        $.toast({
          heading: 'Alerta',
          text: 'Insira uma localização valida!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
      deliver_localizacao.focus();
      permitir = 0;
      return;
    }

    if (deliver_preco.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um preço valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      deliver_preco.focus();
      permitir = 0;
      return;
  }

  $.ajax({
    url  : "app/api/deliver/deliver",
    type : 'post',
    data : {
    add_deliver  : true,
    header       : 'application/json',
    localizacao  : deliver_localizacao.value.trim(),
    latitude     : deliver_latitude.value.trim(),
    longitude    : deliver_longitude.value.trim(),
    preco        : deliver_preco.value.trim()
    },
    dataType: 'json',
    beforeSend : function(){
    load();
    }})
    .done(function(msg){
    if(msg == 1){
        location.href = './?deliver';
        return;
    }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a sua localização!',
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
  $(document).delegate('#deliver-id', 'click', function() {
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
    document.querySelectorAll('.table input[deliver-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' localização(s) selecionada(s) pretende mesmo removela(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhuma localização selecionada!';
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
    document.querySelectorAll('.table input[deliver-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('deliver-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/deliver/deliver",
      type : 'post',
      data : {
      remove_deliver : true,
      header         : 'application/json',
      localizacao_id : _seleted,
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

  //Editar deliver
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-deliver-id').value          = $(this).attr('data-id');
    document.querySelector('#edit-deliver-localizacao').value = $(this).attr('data-localizacao');
    document.querySelector('#edit-deliver-latitude').value    = $(this).attr('data-latitude');
    document.querySelector('#edit-deliver-longitude').value   = $(this).attr('data-longitude');
    document.querySelector('#edit-deliver-preco').value       = $(this).attr('data-preco');
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-deliver').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var deliver_localizacao = document.querySelector('#edit-deliver-localizacao');
    var deliver_latitude    = document.querySelector('#edit-deliver-latitude');
    var deliver_longitude   = document.querySelector('#edit-deliver-longitude');
    var deliver_preco       = document.querySelector('#edit-deliver-preco');
    var localizacao_id      = document.querySelector('#edit-deliver-id');

    if (deliver_localizacao.value.trim() == '') {
        $.toast({
          heading: 'Alerta',
          text: 'Insira uma localização valida!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
      deliver_localizacao.focus();
      permitir = 0;
      return;
    }

    if (deliver_preco.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um preço valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      deliver_preco.focus();
      permitir = 0;
      return;
  }
    
    $.ajax({
      url  : "app/api/deliver/deliver",
      type : 'post',
      data : {
      edit_deliver : true,
      header         : 'application/json',
      localizacao    : deliver_localizacao.value.trim(),
      latitude       : deliver_latitude.value.trim(),
      longitude      : deliver_longitude.value.trim(),
      preco          : deliver_preco.value.trim(),
      localizacao_id : localizacao_id.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
        location.reload();
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

});