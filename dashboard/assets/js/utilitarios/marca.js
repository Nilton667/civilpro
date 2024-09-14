document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;

  //Adicionar marca
  document.getElementById('add-marca').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var marca = document.querySelector('#marca-nome');

    if (marca.value.trim() == '') {
        $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
    marca.focus();
    permitir = 0;
    return;
    }

    $.ajax({
    url  : "app/api/utilitarios/marca",
    type : 'post',
    data : {
    add_marca : true,
    header        : 'application/json',
    marca     : marca.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
    load();
    }})
    .done(function(msg){
    if(msg == 1){
        location.href = './?marcas';
        return;
    }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a sua marca!',
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
  $(document).delegate('#marca-id', 'click', function() {
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
    document.querySelectorAll('.table input[marca-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' marca(s) selecionada(s) pretende mesmo removela(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhuma marca selecionada!';
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
    document.querySelectorAll('.table input[marca-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('marca-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/utilitarios/marca",
      type : 'post',
      data : {
      remove_marca : true,
      header           : 'application/json',
      marca_id     : _seleted,
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

  //Editar marca
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-marca-id').value   = $(this).attr('data-id');
    document.querySelector('#edit-marca-nome').value = $(this).attr('data-marca');
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-marca').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var marca    = document.querySelector('#edit-marca-nome');
    var marca_id = document.querySelector('#edit-marca-id');

    if (marca.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      marca.focus();
      permitir = 0;
      return;
    }
    
    $.ajax({
      url  : "app/api/utilitarios/marca",
      type : 'post',
      data : {
      edit_marca : true,
      header         : 'application/json',
      marca      : marca.value.trim(),
      old_marca  : document.querySelector('#marca-nome-'+marca_id.value.trim()).textContent,
      marca_id   : marca_id.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
          $('#modal-edit-item').modal('hide');
          if (document.querySelector('#marca-nome-'+marca_id.value.trim())) {
              $('#marca-nome-'+marca_id.value.trim()).html(marca.value.trim());
          }
          if (document.querySelector('.modal-edit-'+marca_id.value.trim())) {
              $('.modal-edit-'+marca_id.value.trim()).attr('data-marca', marca.value.trim());
          }
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar a sua marca!',
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