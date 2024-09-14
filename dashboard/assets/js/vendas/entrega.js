document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0; 
  
    //Confirmar entrega
    $(document).delegate('#modal-confirm', 'click', function(e) {
        document.querySelector('#confirm-entrega-id').value   = $(this).attr('data-id');
        document.querySelector('#confirm-factura').innerHTML  = $(this).attr('data-id');
        document.querySelector('#confirm-ref').innerHTML      = $(this).attr('data-ref');
        $('#modal-confirm-item').modal('show');
    });

    //Confirmar compra
    document.getElementById('confirm-entrega').addEventListener('click',function(){

        if(permitir == 0){
            permitir = 1;
        }else{
            return;
        }
    
        var entrega_id = document.querySelector('#confirm-entrega-id');

        $.ajax({
        url  : "app/api/vendas/entrega",
        type : 'post',
        data : {
        confirm_entrega : true,
        header          : 'application/json',
        entrega_id      : entrega_id.value.trim(),
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
            text: 'Não foi possível confirmar a entrega!',
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