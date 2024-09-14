document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;

  //Adicionar dormitorio
  document.getElementById('add-dormitorio').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var dormitorio = document.querySelector('#dormitorio-nome');

    if (dormitorio.value.trim() == '') {
        $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
    dormitorio.focus();
    permitir = 0;
    return;
    }

    $.ajax({
    url  : "app/api/utilitarios/dormitorio",
    type : 'post',
    data : {
    add_dormitorio : true,
    header        : 'application/json',
    dormitorio     : dormitorio.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
    load();
    }})
    .done(function(msg){
    if(msg == 1){
        location.href = './?dormitorios';
        return;
    }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar o seu dormitório!',
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
  $(document).delegate('#dormitorio-id', 'click', function() {
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
    document.querySelectorAll('.table input[dormitorio-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' dormitorio(s) selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum dormitório selecionado!';
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
    document.querySelectorAll('.table input[dormitorio-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('dormitorio-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/utilitarios/dormitorio",
      type : 'post',
      data : {
      remove_dormitorio : true,
      header           : 'application/json',
      dormitorio_id     : _seleted,
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

  //Editar dormitorio
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-dormitorio-id').value   = $(this).attr('data-id');
    document.querySelector('#edit-dormitorio-nome').value = $(this).attr('data-dormitorio');
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-dormitorio').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var dormitorio    = document.querySelector('#edit-dormitorio-nome');
    var dormitorio_id = document.querySelector('#edit-dormitorio-id');

    if (dormitorio.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      dormitorio.focus();
      permitir = 0;
      return;
    }
    
    $.ajax({
      url  : "app/api/utilitarios/dormitorio",
      type : 'post',
      data : {
      edit_dormitorio : true,
      header         : 'application/json',
      dormitorio      : dormitorio.value.trim(),
      old_dormitorio  : document.querySelector('#dormitorio-nome-'+dormitorio_id.value.trim()).textContent,
      dormitorio_id   : dormitorio_id.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
          $('#modal-edit-item').modal('hide');
          if (document.querySelector('#dormitorio-nome-'+dormitorio_id.value.trim())) {
              $('#dormitorio-nome-'+dormitorio_id.value.trim()).html(dormitorio.value.trim());
          }
          if (document.querySelector('.modal-edit-'+dormitorio_id.value.trim())) {
              $('.modal-edit-'+dormitorio_id.value.trim()).attr('data-dormitorio', dormitorio.value.trim());
          }
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar o seu dormitório!',
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