document.addEventListener('DOMContentLoaded', function(){

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

  //Adicionar formador
  document.getElementById('add-formador').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var nome        = document.querySelector('#formador-nome');
    var descricao   = document.querySelector('#message-text');
    descricao.value = editor.getData().trim(); 

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
    }else if (descricao.value.trim() == '') {
      $.toast({
        heading: 'Alerta',
        text: 'Insira a sua descrição!',
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      editor.editing.view.focus();
      permitir = 0;
      return;
    }

    $('#form-formadores').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
    if(msg == 1){
      location.href = './?cursos_formadores';
      return;
    }else if(msg == 0){
      $.toast({
      heading: 'Alerta',
      text: 'Não foi possível registar o novo formador!',
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
    url : 'app/api/curso/formadores',
    resetForm: false
    }).submit();
  
  });  

  //Selecionar itens
  $(document).delegate('#formador-id', 'click', function() {
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
    document.querySelectorAll('.table input[formador-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' formador(s) selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum formador selecionado!';
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
    document.querySelectorAll('.table input[formador-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('formador-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/curso/formadores",
      type : 'post',
      data : {
      remove_formador : true,
      header           : 'application/json',
      formador_id     : _seleted,
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

  //Editar formador
  $(document).delegate('#modal-edit', 'click', function(e) {
    document.querySelector('#edit-formador-id').value   = $(this).attr('data-id');
    document.querySelector('#edit-formador-nome').value = $(this).attr('data-nome');
    document.querySelector('#edit-old-imagem').value    = $(this).attr('data-imagem');
    editorEdicao.setData($(this).attr('data-descricao'));
    $('#modal-edit-item').modal('show');
  });

  //Evento Editar
  document.getElementById('edit-formador').addEventListener('click',function(){

    if(permitir == 0){
      permitir = 1;
    }else{
      return;
    }

    var nome        = document.querySelector('#edit-formador-nome');
    var descricao   = document.querySelector('#message-text-edit');
    descricao.value = editorEdicao.getData().trim();

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
    
    $('#edit-form-formadores').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
      load();
    },
    success: function(msg){
      if(msg == 1){
        location.reload();
        return;
      }else if(msg == 0){
        $.toast({
          heading: 'Alerta',
          text: 'Não foi possível editar o perfil do formador!',
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
        text: err,
        showHideTransition: 'fade',
        icon: 'error',
        loader: true,
      });
      permitir = 0;
      onload();
    },
    dataType: 'json',
    url : 'app/api/curso/formadores',
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