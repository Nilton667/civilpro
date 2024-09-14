document.addEventListener('DOMContentLoaded', function(){
  var permitir  = 0;

  //Visualizar agenda
  $(document).delegate('#view', 'click', function(e) {

    document.querySelector('#view-titulo').innerHTML    = $(this).attr('data-titulo');
    document.querySelector('#view-descricao').innerHTML = $(this).attr('data-descricao');

    $('#modal-view').modal('show');

  });

  //Selecionar itens
  $(document).delegate('#agenda-id', 'click', function() {
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
    document.querySelectorAll('.table input[agenda-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' evento(s) selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum evento selecionado!';
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

    var _seleted = '';
    document.querySelectorAll('.table input[agenda-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('agenda-select')+',';
      }
    });

    $.ajax({
      url  : "app/api/agenda/agenda",
      type : 'post',
      data : {
      remove_agenda : true,
      header        : 'application/json',
      agenda_id     : _seleted,
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

  //Adicionar agenda
  $('#add-agenda').click(function(){

    if(permitir == 0){
        permitir = 1;
    }else{
        return;
    }

    var agenda     = document.querySelector('#agenda-titulo');
    var data       = document.querySelector('#agenda-data');
    var descricao  = document.querySelector('#agenda-descricao');

    if (agenda.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um título valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      agenda.focus();
      permitir = 0;
      return;
    }else if(data.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma data valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      data.focus();
      permitir = 0;
      return;
    }else if (descricao.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma descrição valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      descricao.focus();
      permitir = 0;
      return;
    }
    
    load();

    $('#agenda-form').ajaxForm({
    uploadProgress: function (event, position, total, percentComplete) {},
    success: function(msg){
      if(msg == 1){
        location.href = './?agenda';
        return;
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar o seu evento!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
      }else{
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
    },
    error: function(er){
        $.toast({
          heading: 'Alerta',
          text: er,
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        permitir = 0;
        onload();
    },
    dataType: 'json',
    url : 'app/api/agenda/agenda',
    resetForm: false,
    }).submit();
  });

  //Editar agenda
  $(document).delegate('#modal-edit', 'click', function(e) {

    document.querySelector('#edit-agenda-id').value        = $(this).attr('data-id');
    document.querySelector('#edit-agenda-titulo').value    = $(this).attr('data-titulo');
    document.querySelector('#edit-agenda-data').value      = $(this).attr('data-data');
    document.querySelector('#edit-agenda-descricao').value = $(this).attr('data-descricao');

    $('#modal-edit-agenda').modal('show');

  });

  //Editar agenda
  document.getElementById('edit-agenda').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var agenda     = document.querySelector('#edit-agenda-titulo');
    var data       = document.querySelector('#edit-agenda-data');
    var descricao  = document.querySelector('#edit-agenda-descricao');

    if (agenda.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um título valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      agenda.focus();
      permitir = 0;
      return;
    }else if(data.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma data valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      data.focus();
      permitir = 0;
      return;
    }else if (descricao.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma descrição valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      descricao.focus();
      permitir = 0;
      return;
    }

    load();

    $('#agenda-form-update').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
  
      },
      success: function(msg){
        if(msg == 1){
          location.reload();
          return;
        }else if(msg == 0){
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível editar o seu evento!',
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
      },
      error: function(er){
        $.toast({
          heading: 'Alerta',
          text: er,
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        permitir = 0;
        onload();
      },
      dataType: 'json',
      url : 'app/api/agenda/agenda',
      resetForm: false,
    }).submit();

  });

});