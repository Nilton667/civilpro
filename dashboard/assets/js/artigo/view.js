document.addEventListener('DOMContentLoaded', function(){
  
  var permitir      = 0;

  document.getElementById('remove-artigo').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
      
      var artigo_id = this.getAttribute('artigo-id');
      
      $.ajax({
        url  : "app/api/artigo/artigo",
        type : 'post',
        data : {
        remove_artigo  : true,
        header       : 'application/json',
        artigo_id    : artigo_id,
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
          location.href= './?artigos';
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
