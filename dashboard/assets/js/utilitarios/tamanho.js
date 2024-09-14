document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;

  //Adicionar tamanho
  document.getElementById('add-tamanho').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var tamanho = document.querySelector('#tamanho-nome');

    if (tamanho.value.trim() == '') {
        $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
    tamanho.focus();
    permitir = 0;
    return;
    }

    $.ajax({
    url  : "app/api/utilitarios/tamanho",
    type : 'post',
    data : {
    add_tamanho : true,
    header        : 'application/json',
    tamanho     : tamanho.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
    load();
    }})
    .done(function(msg){
    if(msg == 1){
        location.href = './?tamanhos';
        return;
    }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a sua tamanho!',
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
  $(document).delegate('#tamanho-id', 'click', function() {
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
    document.querySelectorAll('.table input[tamanho-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' tamanho(s) selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum tamanho selecionado!';
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
    document.querySelectorAll('.table input[tamanho-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('tamanho-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/utilitarios/tamanho",
      type : 'post',
      data : {
      remove_tamanho : true,
      header           : 'application/json',
      tamanho_id     : _seleted,
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

  //Editar tamanho
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-tamanho-id').value   = $(this).attr('data-id');
    document.querySelector('#edit-tamanho-nome').value = $(this).attr('data-tamanho');
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-tamanho').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var tamanho    = document.querySelector('#edit-tamanho-nome');
    var tamanho_id = document.querySelector('#edit-tamanho-id');

    if (tamanho.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      tamanho.focus();
      permitir = 0;
      return;
    }
    
    $.ajax({
      url  : "app/api/utilitarios/tamanho",
      type : 'post',
      data : {
      edit_tamanho : true,
      header         : 'application/json',
      tamanho      : tamanho.value.trim(),
      old_tamanho  : document.querySelector('#tamanho-nome-'+tamanho_id.value.trim()).textContent,
      tamanho_id   : tamanho_id.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
          $('#modal-edit-item').modal('hide');
          if (document.querySelector('#tamanho-nome-'+tamanho_id.value.trim())) {
              $('#tamanho-nome-'+tamanho_id.value.trim()).html(tamanho.value.trim());
          }
          if (document.querySelector('.modal-edit-'+tamanho_id.value.trim())) {
              $('.modal-edit-'+tamanho_id.value.trim()).attr('data-tamanho', tamanho.value.trim());
          }
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar a sua tamanho!',
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