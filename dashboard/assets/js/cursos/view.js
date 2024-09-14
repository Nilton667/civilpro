document.addEventListener('DOMContentLoaded', function(){
  
  var permitir      = 0;

  document.getElementById('remove-curso').addEventListener('click',function(){

      if(permitir == 0){
        permitir = 1;
      }else{
        return;
      }
      
      var curso_id = this.getAttribute('curso-id');
      
      $.ajax({
        url  : "app/api/curso/cursos",
        type : 'post',
        data : {
        remove_curso  : true,
        header       : 'application/json',
        cursos_id    : curso_id,
      },
      dataType: 'json',
      beforeSend : function(){
        load();
      }})
      .done(function(msg){
        if(msg == 1){
          location.href= './?cursos';
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
