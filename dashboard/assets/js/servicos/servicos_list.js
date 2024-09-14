document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;
  
    //Selecionar itens
    $(document).delegate('#servicos_list-id', 'click', function() {
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
      document.querySelectorAll('.table input[servicos_list-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted++;
        }
      });
  
      if(_seleted > 0){
        document.querySelector('#remove-content').innerHTML = _seleted+' serviços(s) selecionado(s) pretende mesmo removelo(s)?';
        document.getElementById('remove-item').hidden       = false;
      }else{
        document.querySelector('#remove-content').innerHTML = 'Nenhum serviço selecionado!';
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
      document.querySelectorAll('.table input[servicos_list-select]').forEach(function(element, index){
        if (element.checked) {
          _seleted += element.getAttribute('servicos_list-select')+',';
        }
      });
  
      $.ajax({
        url  : "app/api/servicos/servicos",
        type : 'post',
        data : {
        remove_service : true,
        header      : 'application/json',
        blog_id     : _seleted,
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