document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;

  //Adicionar area
  document.getElementById('add-area').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var area = document.querySelector('#area-nome');

    if (area.value.trim() == '') {
        $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
    area.focus();
    permitir = 0;
    return;
    }

    $.ajax({
    url  : "app/api/utilitarios/area",
    type : 'post',
    data : {
    add_area : true,
    header        : 'application/json',
    area     : area.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
    load();
    }})
    .done(function(msg){
    if(msg == 1){
        location.href = './?areas';
        return;
    }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a sua área!',
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
  $(document).delegate('#area-id', 'click', function() {
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
    document.querySelectorAll('.table input[area-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' área(s) selecionada(s) pretende mesmo removela(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhuma area selecionada!';
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
    document.querySelectorAll('.table input[area-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('area-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/utilitarios/area",
      type : 'post',
      data : {
      remove_area : true,
      header           : 'application/json',
      area_id     : _seleted,
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

  //Editar area
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-area-id').value   = $(this).attr('data-id');
    document.querySelector('#edit-area-nome').value = $(this).attr('data-area');
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-area').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var area    = document.querySelector('#edit-area-nome');
    var area_id = document.querySelector('#edit-area-id');

    if (area.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      area.focus();
      permitir = 0;
      return;
    }
    
    $.ajax({
      url  : "app/api/utilitarios/area",
      type : 'post',
      data : {
      edit_area : true,
      header         : 'application/json',
      area      : area.value.trim(),
      old_area  : document.querySelector('#area-nome-'+area_id.value.trim()).textContent,
      area_id   : area_id.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
          $('#modal-edit-item').modal('hide');
          if (document.querySelector('#area-nome-'+area_id.value.trim())) {
              $('#area-nome-'+area_id.value.trim()).html(area.value.trim());
          }
          if (document.querySelector('.modal-edit-'+area_id.value.trim())) {
              $('.modal-edit-'+area_id.value.trim()).attr('data-area', area.value.trim());
          }
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar a sua área!',
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