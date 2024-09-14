document.addEventListener('DOMContentLoaded', function() {
    var scrollLength = true;
    //Controlo do scroll
    if(document.getElementById('infinity-scroll') && document.getElementById('infinity-load')){
        document.addEventListener('scroll', function(e){
            if(this.documentElement){

                let height    = this.documentElement.scrollHeight;
                let position  = parseInt($(this).scrollTop()) + parseInt(this.documentElement.clientHeight);
                let result    = parseInt(height) - parseInt(position);

                if(result <= 250 && scrollLength){
                    scrollLength = false;
                    InfinityLoad(document.getElementById('infinity-load'), true);
                    render();
                }
                
            }
        });
    }

    //Buscando novas publicações
    function render(){
        if(document.querySelector('#infinity-scroll').lastElementChild.getAttribute('data-id')){
            //Limitador
            let scrollLimiter = document.querySelector('#infinity-scroll')
            .lastElementChild
            .getAttribute('data-id');

            //Categoria
            let categoria = document.querySelector('#infinity-categoria') ?? '';
            if(categoria != ''){ categoria = categoria.getAttribute('data-categoria'); }

            //subcategoria
            let subcategoria = document.querySelector('#infinity-subcategoria') ?? '';
            if(subcategoria != ''){ subcategoria = subcategoria.getAttribute('data-subcategoria'); }

            //Search
            let search    = document.querySelector('#infinity-search') ?? '';
            if(search != ''){ search = search.getAttribute('data-search'); }

            $.ajax({
                url       : "app/api/loadMore",
                type      : 'post',
                data      : {
                getData      : "loadMore",
                categoria    : categoria.trim(),
                subcategoria : subcategoria.trim(),
                search       : search.trim(),
                limiter      : scrollLimiter.trim(),
            },
            beforeSend : function(){ console.log('loadMore...'); }
            })
            .done(function(msg){
                if(msg != 0){
                    $("#infinity-scroll").append(msg);
                    scrollLength = true;

                }else if(msg == 0){
                    scrollLength = false;

                }
                InfinityLoad(document.getElementById('infinity-load'), false);
            })
            .fail(function(jqXHR, textStatus, msg){
                console.error(msg);
                scrollLength = true;
                InfinityLoad(document.getElementById('infinity-load'), false);
            });
        }
    }

    //Loading
    function InfinityLoad(element, action){
        if(action){
            element.removeAttribute('hidden');
            return;
        }
        element.setAttribute('hidden', '');
    }
});