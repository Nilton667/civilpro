document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;

    //Adicionar subcategoria
    document.getElementById('add-subcategoria').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var subcategoria = document.querySelector('#subcategoria-nome');
      var categoria    = document.querySelector('#subcategoria-categoria');
  
      if (subcategoria.value.trim() == ''){
        $.toast({
        heading: 'Alerta',
        text: 'Insira uma subcategoria valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
        subcategoria.focus();
        permitir = 0;
        return;
      }else if (categoria.value.trim() == ''){
        $.toast({
        heading: 'Alerta',
        text: 'Insira uma categoria valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
        categoria.focus();
        permitir = 0;
        return;
      }
  
      $.ajax({
      url  : "app/api/utilitarios/subcategoria",
      type : 'post',
      data : {
      add_subcategoria : true,
      header        : 'application/json',
      subcategoria  : subcategoria.value.trim(),
      categoria     : categoria.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
      load();
      }})
      .done(function(msg){
      if(msg == 1){
          location.href = './?subcategorias';
          return;
      }else if(msg == 0){
          $.toast({
          heading: 'Alerta',
          text: 'Não foi possível registar a sua subcategoria!',
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
    $(document).delegate('#subcategoria-id', 'click', function() {
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
      document.querySelectorAll('.table input[subcategoria-select]').forEach(function(element, index){
          if (element.checked) {
              _seleted++;
          }
      });
  
      if(_seleted > 0){
          document.querySelector('#remove-content').innerHTML = _seleted+' subcategoria(s) selecionada(s) pretende mesmo removela(s)?';
          document.getElementById('remove-item').hidden       = false;
      }else{
          document.querySelector('#remove-content').innerHTML = 'Nenhuma subcategoria selecionada!';
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
    document.querySelectorAll('.table input[subcategoria-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('subcategoria-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/utilitarios/subcategoria",
      type : 'post',
      data : {
      remove_subcategoria : true,
      header           : 'application/json',
      subcategoria_id     : _seleted,
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

//Editar subcategoria
$(document).delegate('#modal-edit', 'click', function(e) {
	document.querySelector('#edit-subcategoria-id').value        = $(this).attr('data-id');
  document.querySelector('#edit-subcategoria-nome').value      = $(this).attr('data-subcategoria');
  document.querySelector('#edit-subcategoria-categoria').value = $(this).attr('data-categoria');
	$('#modal-edit-item').modal('show');
});

//Evento Editar
document.getElementById('edit-subcategoria').addEventListener('click',function(){

  if(permitir == 0){
    permitir = 1;
  }else{
    return;
  }

  var subcategoria    = document.querySelector('#edit-subcategoria-nome');
  var categoria       = document.querySelector('#edit-subcategoria-categoria');
  var subcategoria_id = document.querySelector('#edit-subcategoria-id');

  if (subcategoria.value.trim() == '') {
	  $.toast({
	    heading: 'Alerta',
	    text: 'Insira uma subcategoria valida!',
	    showHideTransition: 'fade',
	    icon: 'error',
	    loader: true,
	  });
    subcategoria.focus();
    permitir = 0;
    return;
  }else if (categoria.value.trim() == ''){
    $.toast({
    heading: 'Alerta',
    text: 'Insira uma categoria valida!',
    showHideTransition: 'fade',
    icon: 'error',
    loader: true,
    });
    categoria.focus();
    permitir = 0;
    return;
  }

  $.ajax({
    url  : "app/api/utilitarios/subcategoria",
    type : 'post',
    data : {
    edit_subcategoria : true,
    header            : 'application/json',
    subcategoria      : subcategoria.value.trim(),
    categoria         : categoria.value.trim(),
    subcategoria_id   : subcategoria_id.value.trim(),
  },
  dataType: 'json',
  beforeSend : function(){
    load();
  }})
  .done(function(msg){
    if(msg == 1){
        $('#modal-edit-item').modal('hide');
        if (document.querySelector('#subcategoria-nome-'+subcategoria_id.value.trim())) {
            $('#subcategoria-nome-'+subcategoria_id.value.trim()).html(subcategoria.value.trim());
        }
        if (document.querySelector('#categoria-nome-'+subcategoria_id.value.trim())) {
          $('#categoria-nome-'+subcategoria_id.value.trim()).html(categoria.value.trim());
        }
        if (document.querySelector('.modal-edit-'+subcategoria_id.value.trim())) {
          $('.modal-edit-'+subcategoria_id.value.trim()).attr('data-subcategoria', subcategoria.value.trim());
          $('.modal-edit-'+subcategoria_id.value.trim()).attr('data-categoria', categoria.value.trim());
        }
    }else if(msg == 0){
      $.toast({
        heading: 'Alerta',
        text: 'Não foi possível editar a sua subcategoria!',
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