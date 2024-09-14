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

  //ckeditor edição
  let editorEdicao;

  ClassicEditor
  .create(document.querySelector('#message-text-edit'),{
    language: 'pt',
    toolbar: {
      items: [
        'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'
      ]
    },
  })
  .then(newEditor  => {
    editorEdicao = newEditor;
  })
  .catch( error => {
      console.error(error);
  });
  //

  //Adicionar pagamento
  document.getElementById('add-pagamento').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var pagamento = document.querySelector('#pagamento-nome');
    var descricao = editor;

    if (pagamento.value.trim() == '') {
        $.toast({
        heading: 'Alerta',
        text: 'Insira um método de pagamento valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
        });
    pagamento.focus();
    permitir = 0;
    return;
    }else if (descricao.getData().trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira a sua descrição!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      descricao.editing.view.focus();
      permitir = 0;
      return;
    }

    $.ajax({
    url  : "app/api/pagamento/pagamento",
    type : 'post',
    data : {
    add_pagamento : true,
    header        : 'application/json',
    pagamento     : pagamento.value.trim(),
    descricao     : descricao.getData().trim(),
    },
    dataType: 'json',
    beforeSend : function(){
    load();
    }})
    .done(function(msg){
    if(msg == 1){
        location.href = './?pagamento';
        return;
    }else if(msg == 0){
      $.toast({
      heading: 'Alerta',
      text: 'Não foi possível registar o novo método de pagamento!',
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
  $(document).delegate('#pagamento-id', 'click', function() {
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
    document.querySelectorAll('.table input[pagamento-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' método(s) de pagamento(s) selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhuma método de pagamento selecionado!';
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
    document.querySelectorAll('.table input[pagamento-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('pagamento-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/pagamento/pagamento",
      type : 'post',
      data : {
      remove_pagamento : true,
      header           : 'application/json',
      pagamento_id     : _seleted,
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

  //Editar pagamento
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-pagamento-id').value   = $(this).attr('data-id');
    document.querySelector('#edit-pagamento-nome').value = $(this).attr('data-pagamento');
    editorEdicao.setData($(this).attr('data-descricao'));
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-pagamento').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var pagamento    = document.querySelector('#edit-pagamento-nome');
    var pagamento_id = document.querySelector('#edit-pagamento-id');

    if (pagamento.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira um método de pagamento valido!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      pagamento.focus();
      permitir = 0;
      return;
    }else if (editorEdicao.getData().trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira uma descrição valida!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      editorEdicao.editing.view.focus();
      permitir = 0;
      return;
    }
    
    $.ajax({
      url  : "app/api/pagamento/pagamento",
      type : 'post',
      data : {
      edit_pagamento : true,
      header         : 'application/json',
      pagamento      : pagamento.value.trim(),
      old_pagamento  : document.querySelector('#pagamento-nome-'+pagamento_id.value.trim()).textContent,
      pagamento_id   : pagamento_id.value.trim(),
      descricao      : editorEdicao.getData().trim()
    },
    dataType: 'json',
    beforeSend : function(){
      load();
    }})
    .done(function(msg){
      if(msg == 1){
        location.reload();
        return;
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar o seu método de pagamento!',
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