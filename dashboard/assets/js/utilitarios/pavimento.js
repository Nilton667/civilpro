document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;
  
    //Adicionar pavimento
    document.getElementById('add-pavimento').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var pavimento = document.querySelector('#pavimento-nome');
  
      if (pavimento.value.trim() == '') {
          $.toast({
          heading: 'Alerta',
          text: 'Insira um nome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
          });
      pavimento.focus();
      permitir = 0;
      return;
      }
  
      $.ajax({
      url  : "app/api/utilitarios/pavimento",
      type : 'post',
      data : {
      add_pavimento : true,
      header        : 'application/json',
      pavimento     : pavimento.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
      load();
      }})
      .done(function(msg){
      if(msg == 1){
          location.href = './?pavimentos';
          return;
      }else if(msg == 0){
          $.toast({
          heading: 'Alerta',
          text: 'Não foi possível registar o seu pavimento!',
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
    $(document).delegate('#pavimento-id', 'click', function() {
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
      document.querySelectorAll('.table input[pavimento-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted++;
        }
      });
  
      if(_seleted > 0){
        document.querySelector('#remove-content').innerHTML = _seleted+' pavimento(s) selecionado(s) pretende mesmo removelo(s)?';
        document.getElementById('remove-item').hidden       = false;
      }else{
        document.querySelector('#remove-content').innerHTML = 'Nenhum pavimento selecionado!';
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
      document.querySelectorAll('.table input[pavimento-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted += element.getAttribute('pavimento-select')+',';
        }
      });
    
      $.ajax({
        url  : "app/api/utilitarios/pavimento",
        type : 'post',
        data : {
        remove_pavimento : true,
        header           : 'application/json',
        pavimento_id     : _seleted,
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
  
    //Editar pavimento
    $(document).delegate('#modal-edit', 'click', function(e) {
      document.querySelector('#edit-pavimento-id').value   = $(this).attr('data-id');
      document.querySelector('#edit-pavimento-nome').value = $(this).attr('data-pavimento');
      $('#modal-edit-item').modal('show');
    });
  
    //Evento Editar
    document.getElementById('edit-pavimento').addEventListener('click',function(){
  
      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
  
      var pavimento    = document.querySelector('#edit-pavimento-nome');
      var pavimento_id = document.querySelector('#edit-pavimento-id');
  
      if (pavimento.value.trim() == '') {
        $.toast({
          heading: 'Alerta',
          text: 'Insira um nome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
        });
        pavimento.focus();
        permitir = 0;
        return;
      }
      
      $.ajax({
        url  : "app/api/utilitarios/pavimento",
        type : 'post',
        data : {
        edit_pavimento : true,
        header         : 'application/json',
        pavimento      : pavimento.value.trim(),
        old_pavimento  : document.querySelector('#pavimento-nome-'+pavimento_id.value.trim()).textContent,
        pavimento_id   : pavimento_id.value.trim(),
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
            $('#modal-edit-item').modal('hide');
            if (document.querySelector('#pavimento-nome-'+pavimento_id.value.trim())) {
                $('#pavimento-nome-'+pavimento_id.value.trim()).html(pavimento.value.trim());
            }
            if (document.querySelector('.modal-edit-'+pavimento_id.value.trim())) {
                $('.modal-edit-'+pavimento_id.value.trim()).attr('data-pavimento', pavimento.value.trim());
            }
        }else if(msg == 0){
          $.toast({
            heading: 'Alerta',
            text: 'Não foi possível editar o seu pavimento!',
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