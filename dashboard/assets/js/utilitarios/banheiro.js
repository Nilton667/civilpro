document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;
  
    //Adicionar banheiro
    document.getElementById('add-banheiro').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var banheiro = document.querySelector('#banheiro-nome');
  
      if (banheiro.value.trim() == '') {
          $.toast({
          heading: 'Alerta',
          text: 'Insira um nome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
          });
      banheiro.focus();
      permitir = 0;
      return;
      }
  
      $.ajax({
      url  : "app/api/utilitarios/banheiro",
      type : 'post',
      data : {
      add_banheiro : true,
      header        : 'application/json',
      banheiro     : banheiro.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
      load();
      }})
      .done(function(msg){
      if(msg == 1){
          location.href = './?banheiros';
          return;
      }else if(msg == 0){
          $.toast({
          heading: 'Alerta',
          text: 'Não foi possível registar a sua banheiro!',
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
    $(document).delegate('#banheiro-id', 'click', function() {
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
      document.querySelectorAll('.table input[banheiro-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted++;
        }
      });
  
      if(_seleted > 0){
        document.querySelector('#remove-content').innerHTML = _seleted+' banheiro(s) selecionado(s) pretende mesmo removelo(s)?';
        document.getElementById('remove-item').hidden       = false;
      }else{
        document.querySelector('#remove-content').innerHTML = 'Nenhum banheiro selecionado!';
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
      document.querySelectorAll('.table input[banheiro-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted += element.getAttribute('banheiro-select')+',';
        }
      });
    
      $.ajax({
        url  : "app/api/utilitarios/banheiro",
        type : 'post',
        data : {
        remove_banheiro : true,
        header           : 'application/json',
        banheiro_id     : _seleted,
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
  
    //Editar banheiro
    $(document).delegate('#modal-edit', 'click', function(e) {
      document.querySelector('#edit-banheiro-id').value   = $(this).attr('data-id');
      document.querySelector('#edit-banheiro-nome').value = $(this).attr('data-banheiro');
      $('#modal-edit-item').modal('show');
    });
  
    //Evento Editar
    document.getElementById('edit-banheiro').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var banheiro    = document.querySelector('#edit-banheiro-nome');
      var banheiro_id = document.querySelector('#edit-banheiro-id');
  
      if (banheiro.value.trim() == '') {
        $.toast({
          heading: 'Alerta',
          text: 'Insira um nome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        banheiro.focus();
        permitir = 0;
        return;
      }
      
      $.ajax({
        url  : "app/api/utilitarios/banheiro",
        type : 'post',
        data : {
        edit_banheiro : true,
        header         : 'application/json',
        banheiro      : banheiro.value.trim(),
        old_banheiro  : document.querySelector('#banheiro-nome-'+banheiro_id.value.trim()).textContent,
        banheiro_id   : banheiro_id.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
            $('#modal-edit-item').modal('hide');
            if (document.querySelector('#banheiro-nome-'+banheiro_id.value.trim())) {
                $('#banheiro-nome-'+banheiro_id.value.trim()).html(banheiro.value.trim());
            }
            if (document.querySelector('.modal-edit-'+banheiro_id.value.trim())) {
                $('.modal-edit-'+banheiro_id.value.trim()).attr('data-banheiro', banheiro.value.trim());
            }
        }else if(msg == 0){
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível editar o seu banheiro!',
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