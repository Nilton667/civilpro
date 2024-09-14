$(document).ready(function(){

    $(function () {
      $('[data-toggle="popover"]').popover();
      $('[data-toggle="tooltip"]').tooltip();
    });

    if(document.querySelector('.preload')){
        $('.preload').css({
            'opacity': 0,
            'pointer-events': 'none'
        });
    }

    if(document.querySelector('.onload')){
        $('.onload').css({
            'opacity': 1,
            'pointer-events': 'auto'
        });
    }

    if(document.querySelector('.image-popup')){
        $('.image-popup').magnificPopup({
          type: 'image',
          preloader: true,
          mainClass: 'mfp-with-zoom',
          zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: function(openerElement) {
              return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
          },
          gallery: {
            enabled: true
          }
        });        
    }

    //Efeito do scroll
    $(document).scroll(function(){
        if(document.querySelector('.document-top')){
            var scroll = $(this).scrollTop();
            var docTop = document.querySelector('.document-top');
    
            if ( document.querySelector('.document-top') && scroll > 100) {
    
                if(docTop.classList.contains('scrolltop') == false){
                    docTop.classList.add('scrolltop');                
                }
    
            }else{
    
                if(docTop.classList.contains('scrolltop')){
                    docTop.classList.remove('scrolltop');                
                }
            }
        }
    });

    //Rolap up
    $('body').delegate('.document-top','click',function(){
        $('html, body').animate({scrollTop: 0}, 600);
    });

    //Filtro de resultados
    if(document.querySelector('.search-input')){
        document.querySelector('.search-input').addEventListener('keydown', function(e){
            if(e.keyCode == 13){
                _search(this.value);
            }
        })
        document.querySelector('.search-input-button').addEventListener('click', function(){
            _search(document.querySelector('.search-input').value);
        })        
    }

    function _search(filter){
        location.href = './s/'+filter;
    }

});

//Anamação scroll
function getPosicaoElemento(elemID){
    var offsetTrail = document.querySelector(elemID);
    var offsetLeft  = 0;
    var offsetTop   = 0;
    while (offsetTrail) {
        offsetLeft += offsetTrail.offsetLeft;
        offsetTop  += offsetTrail.offsetTop;
        offsetTrail = offsetTrail.offsetParent;
    }
    if (navigator.userAgent.indexOf("Mac") != -1 && 
        typeof document.body.leftMargin != "undefined") {
        offsetLeft += document.body.leftMargin;
        offsetTop += document.body.topMargin;
    }
    return {left: offsetLeft, top: offsetTop/* - 66*/};
}

+function ($) {
'use strict';
$("a[href^='#']").on('click', function(e) {
    e.preventDefault()
    var hash = this.hash;
    $('html, body').animate({
        scrollTop: getPosicaoElemento(hash).top
    }, 1000, function(){
        //window.location.hash = hash
    })
})
}(jQuery); 

//Gestão de carregamento
if(document.querySelector('.preload')){
    const DOMLoad  = document.querySelector('.preload');
    function load() {
        if($('.preload').hasClass('load') == false){
            $('.preload').addClass('load');
        }
    }
    function onload() {
        if($('.preload').hasClass('load')){
            $('.preload').removeClass('load');
        }  
    }
}