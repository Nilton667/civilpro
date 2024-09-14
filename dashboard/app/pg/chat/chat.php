<script src="assets/js/jqueryForm.js"></script>

<style type="text/css">
	.content{
		display: flex;
		height: calc(100% - 48.375px);
		overflow: hidden;
	}
	.content-wrapper{
		display: flex!important;
		padding: 0px!important;
		width: 100%;
		height: 100%;
		overflow: hidden;
	}
	.aside-chat-box{
		width: 350px;
		background: #fafafa;
		box-shadow: 5px 5px 8px rgba(0, 0, 0, 0.1);
		height: 100%!important;
		max-height: 100%!important;
		z-index: 100;
		overflow: auto;
	}
	.section-chat-box{
		width: 100%;
		height: 100%;
		background: #fafafa;
		z-index: 99;
		overflow: auto;
	}
	.media-user-chat{
		transition: all 0.3s;
	}
	.media-user-chat.active{
		background: #c9c9c9;
	}
	.media-user-chat:hover{
		background: #c9c9c9;
	}


/*Scroll de janelas*/
.msg-area .campo_de span{
    font-size: 12px;
    display: inline-block;
    width: 100%;
    text-align: center;
    color: rgba(103,103,103,0.7);
    padding: 1px; box-sizing: border-box;
    text-align: right;
}
.msg-area .campo_para span{
    font-size: 12px;
    display: inline-block;
    width: 100%;
    text-align: center;
    color: rgba(103,103,103,0.7);
    padding: 1px; box-sizing: border-box;
    text-align: left;
}
.msg-area i{
    background: #f1f0f0;
    padding: 5px; box-sizing: border-box;
    border-radius: 10px;
    border: 0;
    font-size: 12px; color: #444950;
    cursor: pointer;
    display: inline;
    margin: 0 5px;
}
.campo_de{
    padding-top: 8px;
    display: table;
    width: 100%;
}
.campo_para{
    padding-top: 8px;
    display: table;
    width: 100%;
}
.campo_de p{
    background: #4080ff;
    color: #fafafa;
    font-size: 15px;
    padding: 12px;
    box-sizing: border-box;
    border-radius: 8px;
    float: right;
    max-width: 70%;
    word-break: break-all;
    margin-bottom: 3px!important;
}
.campo_para p{
    background: #f1f0f0;
    color: #444950;
    font-size: 15px;
    padding: 12px;
    box-sizing: border-box;
    border-radius: 8px;
    float: left;
    max-width: 70%;
    word-break: break-all;
    margin-bottom: 3px!important;
}
.campo_chat p{
    padding: 8px;
    box-sizing: border-box;
    word-break: break-all;
    text-align: center;
}
</style>
<div class="aside-chat-box">
	<?php
		$select = DB\Mysql::select(
			'SELECT * FROM usuarios'
		);

		if(is_array($select)){
			foreach ($select as $key => $value) {
				?>
				<div user="<?= $value['id']; ?>" class="media media-user-chat p-2 pointer">
					<img id="userData-image" style="width: 50px!important; height: 50px!important;"
					    class="perfil-image rounded-circle"
					    src="../publico/img/perfil/<?= isset($value['imagem']) && is_file('../publico/img/perfil/'.$value['imagem']) 
					    ? $value['imagem'] 
					    : 'user.png'; ?>">

					<div class="media-body">
						<h5 class="mt-1 ml-1"><?= $value['nome'].' '.$value['sobrenome']; ?></h5>
					</div>
				</div>
				<hr class="m-0">
				<?php
			}
		}else{
			eco("<p class='lead text-center p-2' style='color: #fff;'>Nenhum usuários registado!</p>");
		}
	?>
</div>

<div id="chatbox-dashboard" class="section-chat-box p-relative">
	
	<div class="msg-area pl-2 pr-2" style="height: calc(100% - 50px); overflow: auto;">
		<p class="text-center lead p-2">Nenhum resultado encontrado!</p>
	</div>

	<div class="d-flex" style="height: 50px;">
		<button id="open-chat-input" class="btn btn-secondary" style="border-radius: 0px!important;">
			<i style="font-size: 1.5em;" class="las la-image">
		        <form method="POST" enctype="multipart/form-data" id="FormChatSendFile" 
		        class="p-relative" style="position: absolute; width: 0!important; height: 0!important; pointer-events: none; opacity: 0; z-index: -1;">
	                <input type="hidden" name="sendFile" value="true">
	                <input id="chatSendFileId" type="hidden" name="id_usuario" value="">
	                <input multiple title type="file" name="file[]" id="chatSendFile">
	            </form>
			</i>
		</button>
		<input style="border-radius: 0px;" id="chatText" key="<?= $userData->id; ?>" target=""
		class="form-control" rows="1" placeholder="Mensagem...">
		<button class="chatSend btn btn-primary" style="border-radius: 0px!important;">
			<i style="font-size: 1.5em;" class="las la-paper-plane"></i>
		</button>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){

    if(document.getElementById('chatbox-dashboard')){
        //Selecionar receptor
        $(document).on('click', '.media-user-chat', function(){

        	document.querySelectorAll('.media-user-chat').forEach(function(el){
        		el.classList.remove('active');
        	});

        	$(this).addClass('active');
        	$('#chatText').attr('target', $(this).attr('user'));
            $('#chatSendFileId').val($(this).attr('user'));
        	verificar();

        });

        //Enviar mensagem
        if(document.querySelector('.chatSend')){
            document.querySelector('.chatSend').addEventListener('click', function(){
                activeSend();
            });

            document.addEventListener('keydown', chatKeyCheck);
            function chatKeyCheck(e){
                if(e.keyCode == 13){
                    activeSend();
                }
            }

        }

        function activeSend(){

            if(document.getElementById('chatText').getAttribute('target').trim() == ''){
                $.toast({
                heading: 'Alerta',
                text: 'Nenhum usuário selecionado!',
                showHideTransition: 'fade',
                icon: 'error',
                loader: true,
                });
                return;
            }

            var campo    = $("#chatText");
            var mensagem = campo.val();
            var de       = campo.attr('target');

            if(mensagem.trim() != ''){
                $.post('app/api/chat/chat',{
                    send:     true,
                    mensagem: mensagem,
                    id_usuario: de
                },function (retorno) {
                    if(retorno == 0){
                        $.toast({
                            heading: 'Alerta',
                            text: 'Não foi possível enviar a sua mensagem!',
                            showHideTransition: 'fade',
                            icon: 'error',
                            loader: true,
                        });
                        return;
                    }
                    $('.msg-area').append(retorno);
                    let lista       = document.querySelector('.msg-area');
                    lista.scrollTop = lista.scrollHeight;
                    campo.val('');
                    _v();
                })
            }
        }

        //Enviar arquivos
        if(document.getElementById('open-chat-input')){
            document.getElementById('open-chat-input').addEventListener('click', function(){
                if(document.getElementById('chatSendFileId').value.trim() == ''){
                    $.toast({
                    heading: 'Alerta',
                    text: 'Nenhum usuário selecionado!',
                    showHideTransition: 'fade',
                    icon: 'error',
                    loader: true,
                    });
                    return;
                }
                $('#chatSendFile').click();
            })
        }

        if(document.getElementById('chatSendFile')){
            document.getElementById('chatSendFile').addEventListener('change', function(){
            
            var campo    = $("#chatText");

            load();

            $('#FormChatSendFile').ajaxForm({
            uploadProgress: function (event, position, total, percentComplete) {

            },
            success: function(msg){
              if(msg == 0){
                $.toast({
                  heading: 'Alerta',
                  text: 'Não foi possível fazer o upload do seu arquivo!',
                  showHideTransition: 'fade',
                  icon: 'error',
                  loader: true,
                });
              }else if(msg.indexOf('chat-user') >= 0){
                $('.msg-area').append(msg);
                let lista       = document.querySelector('.msg-area');
                lista.scrollTop = lista.scrollHeight;
                _v();
              }else{
                $.toast({
                  heading: 'Alerta',
                  text: msg,
                  showHideTransition: 'fade',
                  icon: 'error',
                  loader: true,
                });
              }
              onload();
            },
            error: function(er){
                $.toast({
                  heading: 'Alerta',
                  text: er,
                  showHideTransition: 'fade',
                  icon: 'error',
                  loader: true,
                });
                onload();
            },
            dataType: 'json',
            url : 'app/api/chat/chat',
            resetForm: false,
            }).submit();

            })
        }

        //Carregar mensagens
        var antes       = -1;
        var depois      =  0;
        function verificar() {
        	
        	load();

            beforeSend: antes = depois;

            $.post('app/api/chat/chat',{
                load: true,
                id_usuario: $("#chatText").attr('target')
            }, function(x) {
                $('.msg-area').html(x);
                let lista       = document.querySelector('.msg-area');
                lista.scrollTop = lista.scrollHeight;
                _v();

            }, 'json')
            depois ++;

            onload();
        }

        //Visualizador
        function _v(){
            $('.image-link').magnificPopup({
              type: 'image',
              gallery:{
                enabled: true,
              },
              mainClass: 'mfp-with-zoom',
              zoom: {
                  enabled: true,
                  duration: 300,
                  easing: 'ease-in-out',
                  opener: function(openerElement) {
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                  }
              }
            });
        }
    }

    //Verificar mensagens
    function checkSms(){
        beforeSend: antes = depois;
        $.post('app/api/chat/chat',{
            loadSms: true,
            id_usuario: $("#chatText").attr('target')
        }, function(x) {
            if(x.indexOf('chat-user') >= 0){
                $('.msg-area').append(x);
                let lista       = document.querySelector('.msg-area');
                lista.scrollTop = lista.scrollHeight;
                _v();
                $('#chatbox-dashboard').show(200);
            }
        }, 'json')
    }

    setInterval(function(){
        checkSms();
    }, 5000);

});

//Verificar se a mensagem foi lida
function status_lido(){
    $.post('app/api/chat/chat',{
        updateState: true,
    })
}
</script>	