document.addEventListener('DOMContentLoaded', function() {
    
    if (!Array.isArray) {
      Array.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
      };
    }
    
    //Evento DOMContentLoaded
    setTimeout(function(){
        with(document.querySelector('.preload')){
            style.opacity       = '0';
            style.pointerEvents = 'none';
        }
        with(document.querySelector('.onload')){
            style.opacity       = '1';
            style.pointerEvents = 'auto';
        }
        with(document.querySelector('body')){
            style.overflow      = 'auto';
        }        
    }, 1000);

});

//Gestão de carregamento
if(document.querySelector('.preload')){
    const DOMLoad  = document.querySelector('.preload');
    function load() {
        if(!DOMLoad.classList.contains('load')){
            DOMLoad.classList.add('load');
        }
    }
    function onload() {
        if(DOMLoad.classList.contains('load')){
            DOMLoad.classList.remove('load');
        }  
    }
}