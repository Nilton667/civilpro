document.addEventListener('DOMContentLoaded', function(){
  var permitir  = 0;

  //Visualizar imagem
  $('body').delegate('.visualizar-imagem', 'click', function(){
    document.querySelector('#view-image').src = "../publico/img/anuncios/"+this.getAttribute('data-orientacao').toLowerCase()+"/"+this.getAttribute('data-image');
    $('#image-test').modal('show');
  });

  $('body').delegate('.external-url', 'click', function(e) {
    window.open(this.getAttribute('data-url') ,'_blank');
  });

  //Selecionar itens
  $(document).delegate('#anuncio-id', 'click', function() {
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
    document.querySelectorAll('.table input[anuncio-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' anúncio(s) selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum anúncio selecionado!';
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
    document.querySelectorAll('.table input[anuncio-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('anuncio-select')+',';
      }
    });

    $.ajax({
      url  : "app/api/anuncio",
      type : 'post',
      data : {
      remove_anuncio : true,
      header         : 'application/json',
      anuncio_id     : _seleted,
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

  //Adicionar anúncio
  $('#add-anuncio').click(function(){

    if(permitir == 0){
        permitir = 1;
    }else{
        return;
    }

    var anuncio    = document.querySelector('#anuncio-nome');
    var url        = document.querySelector('#anuncio-url');
    var orientacao = document.querySelector('#anuncio-orientacao');

    if (anuncio.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      anuncio.focus();
      permitir = 0;
      return;
    }/*else if(url.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma url valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      url.focus();
      permitir = 0;
      return;
    }*/else if (orientacao.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Selecione uma orientação!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      orientacao.focus();
      permitir = 0;
      return;
    }
    
    load();

    $('#anuncio-form').ajaxForm({
    uploadProgress: function (event, position, total, percentComplete) {

    },
    success: function(msg){
      if(msg == 1){
        location.href = './?anuncio';
        return;
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível registar o seu anúncio!',
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
        fileName('Escolher imagem');
    },
    dataType: 'json',
    url : 'app/api/anuncio',
    resetForm: false,
    }).submit();
  });

  //Editar anuncio
  $(document).delegate('#modal-edit', 'click', function(e) {

    document.querySelector('#edit-anuncio-id').value         = $(this).attr('data-id');
    document.querySelector('#edit-anuncio-nome').value       = $(this).attr('data-anuncio');
    document.querySelector('#edit-anuncio-url').value        = $(this).attr('data-url');
    document.querySelector('#edit-anuncio-orientacao').value = $(this).attr('data-orientacao');
    document.querySelector('#edit-anuncio-estado').value     = $(this).attr('data-estado');

    $('#modal-edit-anuncio').modal('show');

  });

  //Editar anuncio
  document.getElementById('edit-anuncio').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var anuncio    = document.querySelector('#edit-anuncio-nome');
    var orientacao = document.querySelector('#edit-anuncio-orientacao');

    if (anuncio.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      anuncio.focus();
      permitir = 0;
      return;
    }else if (orientacao.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Selecione uma orientação!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      orientacao.focus();
      permitir = 0;
      return;
    }

    load();

    $('#anuncio-form-update').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
  
      },
      success: function(msg){
        if(msg == 1){
          location.reload();
          return;
        }else if(msg == 0){
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível editar o seu anúncio!',
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
      url : 'app/api/anuncio',
      resetForm: false,
    }).submit();

  });
});

function fileName(str){
  if(document.querySelector('#input-file-label')){
    document.querySelector('#input-file-label').textContent = str.trim();
  }
}