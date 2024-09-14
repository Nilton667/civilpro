document.addEventListener('DOMContentLoaded', function(){

  var permitir = 0;

  //Visualizar imagem
  $(document).delegate('.visualizar-imagem', 'click', function(){

      if($(this).attr('data-imagem') == ''){
          $.toast({
              heading: 'Alerta',
              text: 'Selecione no mínimo uma imagem para visualizar!',
              showHideTransition: 'fade',
              icon: 'error',
              loader: true,
          });
          return;
      }

      var file = $(this).attr('data-imagem');
      document.querySelector('#view-image').src = file;
      $('#image-test').modal('show');

  }); 

  //Adicionar artista
  $('#add-artista').click(function(){
    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var nome = document.querySelector('#artista-nome');

    if (nome.value.trim() == '') {
        $.toast({
        heading: 'Alerta',
        text: 'Insira um nome valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
        nome.focus();
    permitir = 0;
    return;
    }

    $('#form-artista').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.href = './?artistas';
        return;
      }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar o seu artista!',
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
    error: function(err){
      $.toast({
        heading: 'Alerta',
        text: err,
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      permitir = 0;
      onload();
    },
    dataType: 'json',
    url : 'app/api/transmicao/artista',
    resetForm: false
    }).submit();
  });  

  //Selecionar itens
  $(document).delegate('#artista-id', 'click', function() {
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
    document.querySelectorAll('.table input[artista-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' artista(s) selecionada(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum artista selecionado!';
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
    document.querySelectorAll('.table input[artista-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('artista-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/transmicao/artista",
      type : 'post',
      data : {
      remove_artista : true,
      header         : 'application/json',
      artista_id     : _seleted,
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

  //Editar artista
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-artista-id').value        = $(this).attr('data-id');
    document.querySelector('#edit-old-imagem').value        = $(this).attr('data-imagem');
    document.querySelector('#edit-artista-nome').value      = $(this).attr('data-nome');
    document.querySelector('#edit-artista-descricao').value = $(this).attr('data-descricao');
    document.querySelector('#edit-old-artista').value       = document.querySelector('#artista-nome-'+$(this).attr('data-id')).textContent.trim();
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-artista').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var nome         = document.querySelector('#edit-artista-nome');
    var descricao    = document.querySelector('#edit-artista-descricao');
    var artista_id   = document.querySelector('#edit-artista-id');

    if (nome.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma artista valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      nome.focus();
      permitir = 0;
      return;
    }

    $('#edit-form-artista').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.reload();
      }else if(msg == 0){
      $.toast({
        heading: 'Alerta',
        text: 'Não foi possível editar o artista selecionado!',
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
    },
    error: function(msg){
      $.toast({
        heading: 'Alerta',
        text: msg,
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      permitir = 0;
      onload();
    },
    dataType: 'json',
    url : 'app/api/transmicao/artista',
    resetForm: false
    }).submit();

  });

});

//fileName
function fileName(str){
  if(document.querySelector('#input-file-label')){
    document.querySelector('#input-file-label').textContent = str.trim();
  }
}

function editFileName(str){
  if(document.querySelector('#edit-input-file-label')){
    document.querySelector('#edit-input-file-label').textContent = str.trim();
  }
}