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

  //Reproduzir audio
  $(document).delegate('.reproduzir-audio', 'click', function(){

    if($(this).attr('data-audio').trim() == ''){
      $.toast({
          heading: 'Alerta',
          text: 'Nenhum resultado encontrado!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      return;
    }else if($(this).attr('data-origem').trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Origem não identificada!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      return;
    }

    if($(this).attr('data-origem').trim() == 'soundcloud'){
      
      document.querySelector('#view-audio').innerHTML = atob($(this).attr('data-audio').trim());

    }else if($(this).attr('data-origem').trim() == 'link'){
      document.querySelector('#view-audio').innerHTML = "<audio controls autoplay style='width: 100%;' src='"+$(this).attr('data-audio').trim()+"'></audio>"
    }else{
      $.toast({
        heading: 'Alerta',
        text: 'Não foi possível carregar o player de audio!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      return;
    }
    //document.querySelector('#view-image').src = file;
    $('#audio-test').modal('show');

}); 

  //Adicionar podcast
  $('#add-podcast').click(function(){
    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var titulo = document.querySelector('#podcast-titulo');
    var origem = document.getElementById('podcast-origem');
    var audio  = document.getElementById('podcast-audio');

    if (titulo.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um título valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      titulo.focus();
      permitir = 0;
      return;
    }else if(origem.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Selecione a origem do audio!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      origem.focus();
      permitir = 0;
      return;
    }else if(audio.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Insira o link ou um arquivo de audio!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      audio.focus();
      permitir = 0;
      return;
    }

    $('#form-podcast').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.href = './?podcast';
        return;
      }else if(msg == 0){
        $.toast({
        heading: 'Alerta',
        text: 'Não foi possível registar o seu podcast!',
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
    url : 'app/api/transmicao/podcast',
    resetForm: false
    }).submit();
  });  

  //Selecionar itens
  $(document).delegate('#podcast-id', 'click', function() {
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
    document.querySelectorAll('.table input[podcast-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' podcast selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum podcast selecionado!';
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
    document.querySelectorAll('.table input[podcast-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('podcast-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/transmicao/podcast",
      type : 'post',
      data : {
      remove_podcast : true,
      header         : 'application/json',
      podcast_id     : _seleted,
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

  //Editar podcast
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-podcast-id').value         = $(this).attr('data-id');
    document.querySelector('#edit-podcast-id_artista').value = $(this).attr('data-id_artista');
    document.querySelector('#edit-podcast-id_album').value   = $(this).attr('data-id_album');
    document.querySelector('#edit-old-imagem').value         = $(this).attr('data-imagem');
    document.querySelector('#edit-podcast-titulo').value     = $(this).attr('data-titulo');
    document.querySelector('#edit-podcast-data').value       = $(this).attr('data-data');
    document.querySelector('#edit-podcast-descricao').value  = $(this).attr('data-descricao');
    document.querySelector('#edit-podcast-origem').value     = $(this).attr('data-origem');
    document.querySelector('#edit-podcast-audio').value      = $(this).attr('data-audio');
    document.querySelector('#edit-old-podcast').value        = document.querySelector('#podcast-titulo-'+$(this).attr('data-id')).textContent.trim();
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-podcast').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var titulo       = document.querySelector('#edit-podcast-titulo');
    var descricao    = document.querySelector('#edit-podcast-descricao');
    var origem       = document.getElementById('edit-podcast-origem');
    var audio       = document.getElementById('edit-podcast-audio');
    var podcast_id   = document.querySelector('#edit-podcast-id');

    if (titulo.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um título valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      titulo.focus();
      permitir = 0;
      return;
    }else if(origem.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Selecione a origem do audio!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      origem.focus();
      permitir = 0;
      return;
    }else if(audio.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Insira o link ou um arquivo de audio!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      audio.focus();
      permitir = 0;
      return;
    }

    $('#edit-form-podcast').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.reload();
      }else if(msg == 0){
      $.toast({
        heading: 'Alerta',
        text: 'Não foi possível editar o podcast selecionado!',
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
    url : 'app/api/transmicao/podcast',
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