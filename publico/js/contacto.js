document.getElementById('send-email').addEventListener('click',function() {
    var nome     = document.querySelector('#recipient-name');
    var email    = document.querySelector('#recipient-email');
    var assunto  = document.querySelector('#recipient-assunto');
    var mensagem = document.querySelector('#recipient-mensagem');

    if (nome.value.trim().length < 6 || nome.value.trim().match(/\s/g) == null) {
        toastr["error"]('Insira o seu nome completo!');
        nome.focus();
        return;
    }else if (email.value.trim().length < 6 || email.value.trim().search('@') == -1 || email.value.trim().match(/\s/g) != null) {
        toastr["error"]('Insira um email valido!');
        email.focus();
        return;
    }else if(assunto.value.trim().length < 4){
        toastr["error"]('Insira um assunto valido!');
        assunto.focus();
        return;
    } else if (mensagem.value.trim() == "") {
        toastr["error"]('Digite a sua mensagem!');
        mensagem.focus();
        return;
    }

    xmlHttp = new XMLHttpRequest();

    if (xmlHttp == null) {
        toastr["error"]('O seu Browser não suporta Ajax experimente actualiza-lo!');
	}else{
    
    //load
    load();

    var link = 'app/envoyer';
    
    formData = new FormData();
    formData.append("send_email", true);
    formData.append("nome", nome.value.trim());
    formData.append("email", email.value.trim());
    formData.append("assunto", assunto.value.trim());
    formData.append("mensagem", mensagem.value.trim());

	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") {

            //onload
            onload();
			if(xmlHttp.responseText == 1){
                toastr["info"]('O seu email foi enviado com sucesso!');
                nome.value         = "";
                email.value        = "";
                assunto.value      = "";
                mensagem.value     = "";
            }else{
                toastr["error"]('Não foi possível enviar o seu email!');
            }
		}
	}

	xmlHttp.open("POST",link,true);
	xmlHttp.send(formData);
  }
});