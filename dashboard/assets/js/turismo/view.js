document.addEventListener('DOMContentLoaded', function(){
  
  var permitir      = 0;

  document.getElementById('remove-city').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
      
      var city_id = this.getAttribute('city_id');
      
      $.ajax({
        url  : "app/api/turismo/city",
        type : 'post',
        data : {
        remove_city  : true,
        header       : 'application/json',
        city_id      : city_id,
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
          location.href= './?city';
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
