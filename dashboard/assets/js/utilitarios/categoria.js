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

  //Adicionar categoria
  $('#add-categoria').click(function(){
    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var categoria = document.querySelector('#categoria-nome');

    if (categoria.value.trim() == '') {
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

    $('#form-categoria').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.href = './?categorias';
        return;
      }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar a sua categoria!',
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
    url : 'app/api/utilitarios/categoria',
    resetForm: false
    }).submit();
  });  

  //Selecionar itens
  $(document).delegate('#categoria-id', 'click', function() {
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
    document.querySelectorAll('.table input[categoria-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' categoria(s) selecionada(s) pretende mesmo removela(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhuma categoria selecionada!';
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
    document.querySelectorAll('.table input[categoria-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('categoria-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/utilitarios/categoria",
      type : 'post',
      data : {
      remove_categoria : true,
      header           : 'application/json',
      categoria_id     : _seleted,
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

  //Editar categoria
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-categoria-id').value        = $(this).attr('data-id');
    document.querySelector('#edit-old-imagem').value          = $(this).attr('data-imagem');
    document.querySelector('#edit-categoria-nome').value      = $(this).attr('data-categoria');
    document.querySelector('#edit-categoria-descricao').value = $(this).attr('data-descricao');

    if(document.querySelector('#edit-categoria-blog')){
      document.querySelector('#edit-categoria-blog').value      = $(this).attr('data-blog');
    }
    
    if(document.querySelector('#edit-categoria-curso')){
      document.querySelector('#edit-categoria-curso').value     = $(this).attr('data-curso');
    }
    
    if(document.querySelector('#edit-categoria-loja')){
      document.querySelector('#edit-categoria-loja').value      = $(this).attr('data-loja');
    }
    
    if(document.querySelector('#edit-categoria-imoveis')){
      document.querySelector('#edit-categoria-imoveis').value   = $(this).attr('data-imoveis');
    }

    document.querySelector('#edit-old-categoria').value       = document.querySelector('#categoria-nome-'+$(this).attr('data-id')).textContent.trim();
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-categoria').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var categoria    = document.querySelector('#edit-categoria-nome');
    var descricao    = document.querySelector('#edit-categoria-descricao');
    var categoria_id = document.querySelector('#edit-categoria-id');

    if (categoria.value.trim() == '') {
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

    $('#edit-form-categoria').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.reload();
    }else if(msg == 0){
      $.toast({
        heading: 'Alerta',
        text: 'Não foi possível editar a sua categoria!',
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
    error: function(err){
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
    url : 'app/api/utilitarios/categoria',
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