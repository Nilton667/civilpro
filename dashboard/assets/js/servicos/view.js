document.addEventListener('DOMContentLoaded', function(){
  
  var permitir      = 0;

  document.getElementById('remove-servico').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
      
      var blog_id = this.getAttribute('servico-id');
      
      $.ajax({
        url  : "app/api/servicos/servicos",
        type : 'post',
        data : {
        remove_service  : true,
        header       : 'application/json',
        blog_id      : blog_id,
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
          location.href= './?servicos_list';
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
