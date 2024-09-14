document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0; 
  
    //Selecionar itens
    $(document).delegate('#pendente-id', 'click', function() {
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
      document.querySelectorAll('.table input[pendente-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted++;
        }
      });
  
      if(_seleted > 0){
        document.querySelector('#remove-content').innerHTML = _seleted+' curso(s) selecionado(s) pretende mesmo removelo(s)?';
        document.getElementById('remove-item').hidden       = false;
      }else{
        document.querySelector('#remove-content').innerHTML = 'Nenhum curso selecionado!';
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
      document.querySelectorAll('.table input[pendente-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted += element.getAttribute('pendente-select')+',';
        }
      });
    
      $.ajax({
        url  : "app/api/curso/pendentes",
        type : 'post',
        data : {
        remove_compra : true,
        header        : 'application/json',
        pendente_id  : _seleted,
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
  
    //Confirmar compra pendente
    $(document).delegate('#modal-confirm', 'click', function(e) {
        document.querySelector('#confirm-fatura').innerHTML  = $(this).attr('data-id');
        document.querySelector('#confirm-pendente-id').value = $(this).attr('data-id');
        $('#modal-confirm-item').modal('show');
    });

    //Confirmar compra
    document.getElementById('confirm-pendente').addEventListener('click',function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }
    
        var pendente_id = document.querySelector('#confirm-pendente-id');

        $.ajax({
        url  : "app/api/curso/pendentes",
        type : 'post',
        data : {
        confirm_compra : true,
        header         : 'application/json',
        pendente_id    : pendente_id.value.trim(),
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
            text: 'Não foi possível confirmar esta compra!',
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