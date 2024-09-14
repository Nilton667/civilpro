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

  //Adicionar album
  $('#add-album').click(function(){
    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var nome = document.querySelector('#album-nome');

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

    $('#form-album').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.href = './?album';
        return;
      }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar o seu album!',
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
    url : 'app/api/transmicao/album',
    resetForm: false
    }).submit();
  });  

  //Selecionar itens
  $(document).delegate('#album-id', 'click', function() {
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
    document.querySelectorAll('.table input[album-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' album selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum album selecionado!';
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
    document.querySelectorAll('.table input[album-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('album-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/transmicao/album",
      type : 'post',
      data : {
      remove_album : true,
      header         : 'application/json',
      album_id     : _seleted,
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

  //Editar album
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-album-id').value        = $(this).attr('data-id');
    document.querySelector('#edit-old-imagem').value      = $(this).attr('data-imagem');
    document.querySelector('#edit-album-nome').value      = $(this).attr('data-nome');
    document.querySelector('#edit-album-data').value      = $(this).attr('data-data');
    document.querySelector('#edit-album-descricao').value = $(this).attr('data-descricao');
    document.querySelector('#edit-old-album').value       = document.querySelector('#album-nome-'+$(this).attr('data-id')).textContent.trim();
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-album').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var nome         = document.querySelector('#edit-album-nome');
    var descricao    = document.querySelector('#edit-album-descricao');
    var album_id   = document.querySelector('#edit-album-id');

    if (nome.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma album valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      nome.focus();
      permitir = 0;
      return;
    }

    $('#edit-form-album').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.reload();
      }else if(msg == 0){
      $.toast({
        heading: 'Alerta',
        text: 'Não foi possível editar o album selecionado!',
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
    url : 'app/api/transmicao/album',
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