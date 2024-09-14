document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;

    $('body').delegate('#feedback-visualize', 'click', function(e){
        $('#show-feedback .modal-body').html(this.getAttribute('data-feedback'));
        $('#show-feedback').modal('show');
    });

    //Selecionar itens
    $(document).delegate('#feedback-id', 'click', function() {
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
        document.querySelectorAll('.table input[feedback-select]').forEach(function(element, index){
            if (element.checked) {
                _seleted++;
            }
        });
    
        if(_seleted > 0){
            document.querySelector('#remove-content').innerHTML = _seleted+' feedback selecionado(s) pretende mesmo removelo(s)?';
            document.getElementById('remove-item').hidden       = false;
        }else{
            document.querySelector('#remove-content').innerHTML = 'Nenhum feedback selecionado!';
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
    document.querySelectorAll('.table input[feedback-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('feedback-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/feedback",
      type : 'post',
      data : {
      remove_feedback : true,
      header          : 'application/json',
      feedback_id     : _seleted,
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