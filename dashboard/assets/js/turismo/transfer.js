document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;
  
    //Informações adicionais
    $('body').delegate('#modal-info', 'click', function(e){
      
      $('#modal-info-view .info-nome').html($(this).attr('data-nome'));
      $('#modal-info-view .info-email').html($(this).attr('data-email'));
      $('#modal-info-view .info-contacto').html($(this).attr('data-contacto'));
      $('#modal-info-view .info-morada').html($(this).attr('data-morada'));
      $('#modal-info-view .info-total').html($(this).attr('data-total'));
  
      $('#modal-info-view').modal('show');
    });
  
    //Selecionar itens
    $(document).delegate('#transfer-id', 'click', function() {
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
      document.querySelectorAll('.table input[transfer-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted++;
        }
      });
  
      if(_seleted > 0){
        document.querySelector('#remove-content').innerHTML = _seleted+' transfer selecionado(s) pretende mesmo removelo(s)?';
        document.getElementById('remove-item').hidden       = false;
      }else{
        document.querySelector('#remove-content').innerHTML = 'Nenhum transfer selecionado!';
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
      document.querySelectorAll('.table input[transfer-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted += element.getAttribute('transfer-select')+',';
        }
      });
    
      $.ajax({
        url  : "app/api/turismo/transfer",
        type : 'post',
        data : {
        remove_transfer : true,
        header      : 'application/json',
        transfer_id  : _seleted,
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
  
  });