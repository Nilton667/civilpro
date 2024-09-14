/* ---------------------------------------------------------------------- */
/* Contador
/* ---------------------------------------------------------------------- */
(function($, undefined) {
	'use strict';

	$(function() {
		$('.contador').each(function() {
			var days = $('.dias .conta', this);
			var hours = $('.horas .conta', this);
			var minutes = $('.minutos .conta', this);
			var seconds = $('.segundos .conta', this);

			var until = parseInt($(this).data('until'), 10);

			var updateTime = function() {
				var now = Math.round( (+new Date()) / 1000 );

				if(until <= now) {
					clearInterval(0);
					seconds.text(0);
					minutes.text(0);
					hours.text(0);
					days.text(0);
					return;
				}

				var left = until-now;

				seconds.text(left%60);

				left     = Math.floor(left/60);
				minutes.text(left%60);

				left     = Math.floor(left/60);
				hours.text(left%24);

				left     = Math.floor(left/24);
				days.text(left);
			};

			var interval = setInterval(updateTime, 1000);
		});
	});
})(jQuery);

$('#add-upload').hide();
document.addEventListener('DOMContentLoaded', function(){
	
	let permitir = 0;
	
	if (document.getElementById('add-cronometro')) {

    //Criar novo cronometro
    $('#add-cronometro').click(function(){

		var titulo = document.querySelector('#cronometro-titulo');
    var data   = document.querySelector('#cronometro-data');
    var hora   = document.querySelector('#timepicker');

    console.log(hora);
	
		if(permitir == 0){
			permitir = 1;
		}else{
			return;
		}
	
		if(titulo.value.trim() == ''){
			$.toast({
				heading: 'Alerta',
				text: 'Insira um título valido!',
				showHideTransition: 'fade',
				icon: 'error',
				loader: true,
			});
			titulo.focus();
			permitir = 0;
			return;
		}else if(data.value.trim() == ''){
			$.toast({
				heading: 'Alerta',
				text: 'Insira uma data valida!',
				showHideTransition: 'fade',
				icon: 'error',
				loader: true,
			});
			data.focus();
			permitir = 0;
			return;
		}else if(hora.value.trim() == ''){
			$.toast({
				heading: 'Alerta',
				text: 'Insira uma hora valida!',
				showHideTransition: 'fade',
				icon: 'error',
				loader: true,
			});
			hora.focus();
			permitir = 0;
			return;
    }

    $('#cronometro-form').ajaxForm({
      uploadProgress: function (event, position, total, percentComplete) {
        $('#add-input').hide();
        $('#modal-add-item .close').hide();
        $('#add-upload').show();
        with(document.querySelector('#add-upload .progress-bar').style){
            width = percentComplete+'%';
        }
      },
      success: function(msg){
    		if (msg == 1) {
    			location.href = './?cronometro';
    			return;           
    		}else if(msg == 0){
    			$.toast({
    			heading: 'Alerta',
    			text: 'Não foi possível registar o seu evento!',
    			showHideTransition: 'fade',
    			icon: 'error',
    			loader: true,
    			}); 
    		}else{
    			$.toast({
    			heading: 'Alerta',
    			text: msg,
    			showHideTransition: 'fade',
    			icon: 'error',
    			loader: true,
    			});  
    		}
    		permitir = 0;
        $('#add-upload').hide();
        $('#modal-add-item .close').show();
        $('#add-input').show();
        with(document.querySelector('#add-upload .progress-bar').style){
          width = '0%';
        }
      },
      error: function(err){
          $.toast({
              heading: 'Alerta',
              text: err,//'Ocorreu um problema de rede tente novamente mais tarde!',
              showHideTransition: 'fade',
              icon: 'error',
              loader: true,
          });
          permitir = 0;
          $('#add-upload').hide();
          $('#modal-add-item .close').show();
          $('#add-input').show();
          with(document.querySelector('#add-upload .progress-bar').style){
            width = '0%';
          }
      },
      //dataType: 'json',
      url : 'app/api/cronometro/cronometro',
      resetForm: false
      }).submit();

	});

	}

  //Deletar
  if (document.querySelector('#modal-delete')){
    document.querySelector('#modal-delete').addEventListener('click', function(){

    _seleted = 0;
    document.querySelectorAll('input[cronometro-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted++;
      }
    });

    if(_seleted > 0){
      document.querySelector('#remove-content').innerHTML = _seleted+' evento(s) selecionado(s) pretende mesmo removelo(s)?';
      document.getElementById('remove-item').hidden       = false;
    }else{
      document.querySelector('#remove-content').innerHTML = 'Nenhum evento selecionado!';
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
    document.querySelectorAll('input[cronometro-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('cronometro-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/cronometro/cronometro",
      type : 'post',
      data : {
	  remove_Cronometro : true,
      header           : 'application/json',
      cronometro_id    : _seleted,
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

//fileName
function fileName(str){
    if(document.querySelector('#input-file-label')){
        document.querySelector('#input-file-label').textContent = str.trim();
    }
}