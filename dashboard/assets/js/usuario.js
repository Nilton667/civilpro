document.addEventListener('DOMContentLoaded', function(){

    var permitir = 0;

    $('body').delegate('#usuario-visualize', 'click', function(e){
        $('#show-usuario #modal-user-nome').html(this.getAttribute('data-nome'));
        $('#show-usuario #modal-user-identificacao').html(this.getAttribute('data-identificacao'));
        $('#show-usuario #modal-user-nacionalidade').html(this.getAttribute('data-nacionalidade'));
        $('#show-usuario #modal-user-morada').html(this.getAttribute('data-morada'));
        if(this.getAttribute('data-conta') == 1){
            conta = '<span style="color: green;"><b>Verificada</b></span>';
        }else{
            conta = '<span style="color: red;"><b>Não verificada</b></span>';
        }
        $('#show-usuario #modal-user-conta').html(conta);
        $('#show-usuario').modal('show');
    });

    //Selecionar itens
    $(document).delegate('#usuario-id', 'click', function() {
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
        document.querySelectorAll('.table input[usuario-select]').forEach(function(element, index){
            if (element.checked) {
                _seleted++;
            }
        });
    
        if(_seleted > 0){
            document.querySelector('#remove-content').innerHTML = _seleted+' usuário(s) selecionado(s) pretende mesmo removelo(s)?';
            document.getElementById('remove-item').hidden       = false;
        }else{
            document.querySelector('#remove-content').innerHTML = 'Nenhum usuário selecionado!';
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
    document.querySelectorAll('.table input[usuario-select]').forEach(function(element, index){
      if (element.checked) {
        _seleted += element.getAttribute('usuario-select')+',';
      }
    });
  
    $.ajax({
      url  : "app/api/usuario",
      type : 'post',
      data : {
      remove_usuario  : true,
      header          : 'application/json',
      usuario_id      : _seleted,
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

////////////////
//cadastro
document.querySelector('#cadastrar').addEventListener('click',function(){
  cadastrar();    
});

function cadastrar() {

  if(permitir == 0){
      permitir = 1;
  }else{
      return;
  }

  var nome          = document.getElementById('nome');
  var sobrenome     = document.getElementById('sobrenome');
  var email         = document.getElementById('email');
  var nacionalidade = document.getElementById('nacionalidade');
  var genero        = document.getElementById('genero');
  var senha         = document.getElementById('senha');

  if(nome.value.trim().length < 4 || nome.value.trim().length > 9 || (/\s/g.test(nome.value.trim())) == true){
      $.toast({
          heading: 'Alerta',
          text: 'Insira um nome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      nome.focus();
      permitir = 0;
      return;
  }else if(sobrenome.value.trim().length < 4 || sobrenome.value.trim().length > 9 || (/\s/g.test(sobrenome.value.trim())) == true){
      $.toast({
          heading: 'Alerta',
          text: 'Insira um sobrenome valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      sobrenome.focus();
      permitir = 0;
      return;
  }else if(email.value.trim().length < 6 || email.value.search('@') == -1){
      $.toast({
          heading: 'Alerta',
          text: 'Insira um email valido!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      email.focus();
      permitir = 0;
      return;
  }else if(nacionalidade.value.trim() == ''){
      $.toast({
          heading: 'Alerta',
          text: 'Selecione a sua nacionalidade!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      nacionalidade.focus();
      permitir = 0;
      return;
  }else if(genero.value.trim() == ''){
      $.toast({
          heading: 'Alerta',
          text: 'Selecione o seu genero!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      genero.focus();
      permitir = 0;
      return;
  }else if(senha.value.trim().length < 6){
      $.toast({
          heading: 'Alerta',
          text: 'A senha deve ter no minímo 6 caracteres!',
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
      senha.focus();
      permitir = 0;
      return;
  }

  $.ajax({
      url  : "app/api/usuario",
      type : 'post',
      data : {
      cadastro      : true,
      header        : 'application/json',
      nome          : nome.value.trim(),
      sobrenome     : sobrenome.value.trim(),
      email         : email.value.trim(),
      nacionalidade : nacionalidade.value.trim(),
      genero        : genero.value.trim(),
      senha         : senha.value.trim(),
  },
  dataType: 'json',
  beforeSend : function(){
      load();
  }})
  .done(function(msg){
      
      if (msg == 1) {
          $.toast({
              heading: 'Alerta',
              text: 'Esta conta foi registada com sucesso!',
              icon: 'info',
              loader: true,
              loaderBg: '#0088bd'
          });
          setTimeout(function(){ location.href = "./?usuarios"; }, 3000);
          return;           
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
      onload();

  })
  .fail(function(jqXHR, textStatus, msg){
      onload();
      permitir = 0;
      $.toast({
          heading: 'Alerta',
          text: msg,
          showHideTransition: 'fade',
          icon: 'error',
          loader: true,
      });
  });
}

});