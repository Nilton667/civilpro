document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;

  //Adicionar cor
  document.getElementById('add-cor').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var cor = document.querySelector('#cor-nome');

    if (cor.value.trim() == '') {
        $.toast({
        heading: 'Alerta',
        text: 'Insira uma cor valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
    cor.focus();
    permitir = 0;
    return;
    }

    $.ajax({
    url  : "app/api/utilitarios/cor",
    type : 'post',
    data : {
    add_cor : true,
    header        : 'application/json',
    cor     : cor.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
    load();
    }})
    .done(function(msg){
    if(msg == 1){
        location.href = './?cores';
        return;
    }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a sua cor!',
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
  $(document).delegate('#cor-id', 'click', function() {
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
    document.querySelectorAll('.table input[cor-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' cor(es) selecionada(s) pretende mesmo removela(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhuma cor selecionada!';
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
    document.querySelectorAll('.table input[cor-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('cor-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/utilitarios/cor",
      type : 'post',
      data : {
      remove_cor : true,
      header           : 'application/json',
      cor_id     : _seleted,
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

  //Editar cor
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-cor-id').value   = $(this).attr('data-id');
    document.querySelector('#edit-cor-nome').value = $(this).attr('data-cor');
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-cor').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var cor    = document.querySelector('#edit-cor-nome');
    var cor_id = document.querySelector('#edit-cor-id');

    if (cor.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma cor valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      cor.focus();
      permitir = 0;
      return;
    }
    
    $.ajax({
      url  : "app/api/utilitarios/cor",
      type : 'post',
      data : {
      edit_cor : true,
      header         : 'application/json',
      cor      : cor.value.trim(),
      old_cor  : document.querySelector('#cor-nome-'+cor_id.value.trim()).textContent,
      cor_id   : cor_id.value.trim(),
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
          $('#modal-edit-item').modal('hide');
          if (document.querySelector('#cor-nome-'+cor_id.value.trim())) {
              $('#cor-nome-'+cor_id.value.trim()).html(cor.value.trim());
          }
          if (document.querySelector('.modal-edit-'+cor_id.value.trim())) {
              $('.modal-edit-'+cor_id.value.trim()).attr('data-cor', cor.value.trim());
          }
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar a sua cor!',
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