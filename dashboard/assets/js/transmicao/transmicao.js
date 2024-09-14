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
  
    //Reproduzir video
    $(document).delegate('.reproduzir-video', 'click', function(){
  
      if($(this).attr('data-video').trim() == ''){
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
  
      if($(this).attr('data-origem').trim() == 'youtube'){
        
        document.querySelector('#view-video').innerHTML = atob($(this).attr('data-video').trim());
  
      }else if($(this).attr('data-origem').trim() == 'link'){
        document.querySelector('#view-video').innerHTML = "<video controls autoplay style='width: 100%;' src='"+$(this).attr('data-video').trim()+"'></video>"
      }else{
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível carregar o player de vídeo!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        return;
      }
      $('#video-test').modal('show');
  
  }); 
  
    //Adicionar transmicao
    $('#add-transmicao').click(function(){
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var titulo = document.querySelector('#transmicao-titulo');
      var origem = document.getElementById('transmicao-origem');
      var video  = document.getElementById('transmicao-video');
  
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
          text: 'Selecione a origem do viideo!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        origem.focus();
        permitir = 0;
        return;
      }else if(video.value.trim() == ''){
        $.toast({
          heading: 'Alerta',
          text: 'Insira o link ou um arquivo de video!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        video.focus();
        permitir = 0;
        return;
      }
  
      $('#form-transmicao').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
        load();
      },
      success: function(msg){
        if(msg == 1){
          location.href = './?transmicao';
          return;
        }else if(msg == 0){
          $.toast({
          heading: 'Alerta',
          text: 'Não foi possível registar o seu vídeo!',
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
      url : 'app/api/transmicao/transmicao',
      resetForm: false
      }).submit();
    });  
  
    //Selecionar itens
    $(document).delegate('#transmicao-id', 'click', function() {
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
      document.querySelectorAll('.table input[transmicao-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted++;
        }
      });
  
      if(_seleted > 0){
        document.querySelector('#remove-content').innerHTML = _seleted+' vídeo(s) selecionado(s) pretende mesmo removelo(s)?';
        document.getElementById('remove-item').hidden       = false;
      }else{
        document.querySelector('#remove-content').innerHTML = 'Nenhum vídeo selecionado!';
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
      document.querySelectorAll('.table input[transmicao-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted += element.getAttribute('transmicao-select')+',';
        }
      });
    
      $.ajax({
        url  : "app/api/transmicao/transmicao",
        type : 'post',
        data : {
        remove_transmicao : true,
        header         : 'application/json',
        transmicao_id     : _seleted,
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
  
    //Editar transmicao
    $(document).delegate('#modal-edit', 'click', function(e) {
      document.querySelector('#edit-transmicao-id').value         = $(this).attr('data-id');
      document.querySelector('#edit-transmicao-id_artista').value = $(this).attr('data-id_artista');
      document.querySelector('#edit-transmicao-id_album').value   = $(this).attr('data-id_album');
      document.querySelector('#edit-old-imagem').value         = $(this).attr('data-imagem');
      document.querySelector('#edit-transmicao-titulo').value     = $(this).attr('data-titulo');
      document.querySelector('#edit-transmicao-data').value       = $(this).attr('data-data');
      document.querySelector('#edit-transmicao-descricao').value  = $(this).attr('data-descricao');
      document.querySelector('#edit-transmicao-origem').value     = $(this).attr('data-origem');
      document.querySelector('#edit-transmicao-video').value      = $(this).attr('data-video');
      document.querySelector('#edit-old-transmicao').value        = document.querySelector('#transmicao-titulo-'+$(this).attr('data-id')).textContent.trim();
      $('#modal-edit-item').modal('show');
    });
  
    //Evento Editar
    document.getElementById('edit-transmicao').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var titulo       = document.querySelector('#edit-transmicao-titulo');
      var descricao    = document.querySelector('#edit-transmicao-descricao');
      var origem       = document.getElementById('edit-transmicao-origem');
      var video       = document.getElementById('edit-transmicao-video');
      var transmicao_id   = document.querySelector('#edit-transmicao-id');
  
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
          text: 'Selecione a origem do video!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        origem.focus();
        permitir = 0;
        return;
      }else if(video.value.trim() == ''){
        $.toast({
          heading: 'Alerta',
          text: 'Insira o link ou um arquivo de vídeo!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        video.focus();
        permitir = 0;
        return;
      }
  
      $('#edit-form-transmicao').ajaxForm({
        uploadProgress: function (event, position, total, percentComplete) {
        load();
      },
      success: function(msg){
        if(msg == 1){
          location.reload();
        }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar o vídeo selecionado!',
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
      url : 'app/api/transmicao/transmicao',
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