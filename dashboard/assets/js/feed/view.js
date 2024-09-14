document.addEventListener('DOMContentLoaded', function(){
  
  var permitir      = 0;

  document.getElementById('remove-post').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
      
      var blog_id = this.getAttribute('post-id');
      
      $.ajax({
        url  : "app/api/feed/blog",
        type : 'post',
        data : {
        remove_post  : true,
        header       : 'application/json',
        blog_id      : blog_id,
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
          location.href= './?feed';
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
