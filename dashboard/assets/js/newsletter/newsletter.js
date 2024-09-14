document.addEventListener('DOMContentLoaded', function(){

  $.fn.modal.Constructor.prototype._enforceFocus = function(){};

  //ckeditor
  let editor;
  var permitir = 0;

  ClassicEditor
  .create(document.querySelector('#message-text'),{
    language: 'pt',
    toolbar: {
      items: [
        'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
      ]
    },
  })
  .then(newEditor  => {
      editor = newEditor;
  })
  .catch( error => {
      console.error(error);
  });
  //

  //Adicionar email a newsletter
  if (document.getElementById('add-user')) {

      document.getElementById('add-user').addEventListener('click',function(){
      var email = document.querySelector('#subscrever-email');

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }

      if(email.value.trim().length < 6 || email.value.search('@') == -1){
        $.toast({
            heading: 'Alerta',
            text: 'Insira um email valido!',
            showHideTransition: 'fade',
            icon: 'error',
            loader: true,
        });
        email.focus();
        permitir = 0;
        return;
      }

      $.ajax({
        url  : "app/api/newsletter/newsletter",
        type : 'post',
        data : {
        add_newsletter  : true,
        header : 'application/json',
        email  : email.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if (msg == 1) {
          location.href = './?newsletter';
          return;           
        }else if(msg == 0){
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível registar esta conta de email!',
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

  }

  //Selecionar itens
  $(document).delegate('#newsletter-id', 'click', function() {
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
    document.querySelectorAll('.table input[newsletter-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' conta(s) selecionada(s) pretende mesmo removela(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhuma conta selecionada!';
      document.getElementById('remove-item').hidden       = true;
    }

    $('#modal-remove-item').modal('show');

    });
  }

  //Evento deletar
  document.getElementById('remove-item').addEventListener('click',function(){
    
    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }
    
    var _seleted = '';
    document.querySelectorAll('.table input[newsletter-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('newsletter-select')+',';
      }
    });

    $.ajax({
      url  : "app/api/newsletter/newsletter",
      type : 'post',
      data : {
      remove_newsletter  : true,
      header             : 'application/json',
      user_id            : _seleted,
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

  //Send mail
  document.getElementById('newsletter-send-email').addEventListener('click',function(){

    if(permitir == 0){
        permitir = 1;
    }else{
        return;
    }

    var emissor  = document.getElementById('mail-emissor');
    var assunto  = document.getElementById('mail-assunto');
    var mensagem = editor;

    if(emissor.value.trim() == ''){
      $.toast({
        heading: 'Alerta',
        text: 'Insira o nome do emissor!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      emissor.focus();
      permitir = 0;
      return;
    }else if(assunto.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um assunto valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      assunto.focus();
      permitir = 0;
      return;
    }else if (mensagem.getData().trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira a sua mensagem!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      mensagem.editing.view.focus();
      permitir = 0;
      return;
    }

    $.ajax({
      url  : "app/api/newsletter/newsletter",
      type : 'post',
      data : {
      send     : true,
      header   : 'application/json',
      emissor  : emissor.value.trim(),
      assunto  : assunto.value.trim(),
      mensagem : mensagem.getData().trim(),
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
        $.toast({
          heading: 'Alerta',
          text: 'O seu email foi enviado com sucesso para todos os seus destinatários!',
          icon: 'info',
          loader: true,
          loaderBg: '#0088bd',
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
});
