<?php

include_once '../control.php';

Class LoadMore extends Conexao{

    private $limiter, $search, $categoria, $subcategoria;

    function __construct(){

        $this->limiter    = post('limiter', false) && is_numeric(post('limiter', false))
        ? filterInt(post('limiter')) 
        : DEFAULT_INT;

        $this->search     = post('search', false)
        ? filterVar(post('search'))
        : '';

        $this->categoria  = post('categoria', false)
        ? filterVar(post('categoria'))
        : '';

        $this->subcategoria  = post('subcategoria', false)
        ? filterVar(post('subcategoria'))
        : '';

    }

    function getData()
    {
        if ($this->limiter <= 0):
            $SELECT = "SELECT * FROM blog WHERE estado = 1 ORDER BY id DESC LIMIT 15";
            $select = DB\Mysql::select(
                $SELECT,
                []
            );

        else:
            if($this->categoria != '' && $this->search == ''){ //Pg -> Categoria
                if($this->subcategoria != ''){
                    $SELECT = "SELECT * FROM blog WHERE estado = 1 AND categoria = :categoria AND subcategoria LIKE '%$this->subcategoria%' AND id < :limiter ORDER BY id DESC LIMIT 15";
                }else{
                    $SELECT = "SELECT * FROM blog WHERE estado = 1 AND categoria = :categoria AND id < :limiter ORDER BY id DESC LIMIT 15";
                }
                $select = DB\Mysql::select(
                    $SELECT,
                    ['categoria' => $this->categoria, 'limiter' => $this->limiter]
                );

            }else if($this->categoria == '' && $this->search != ''){ //Pg -> pesquisa todas categorias
                $SELECT = "SELECT * FROM blog WHERE estado = 1 AND titulo LIKE '%$this->search' AND id < :limiter OR estado = 1 AND subtitulo LIKE '%$this->search%' AND id < :limiter ORDER BY id DESC LIMIT 15";
                $select = DB\Mysql::select(
                    $SELECT,
                    ['limiter' => $this->limiter]
                );

            } else if($this->categoria != '' && $this->search != ''){ //Pg -> pesquisa por categoria
                $SELECT = "SELECT * FROM blog WHERE estado = 1 AND titulo LIKE '%$this->search' AND categoria = :categoria AND id < :limiter OR estado = 1 AND subtitulo LIKE '%$this->search%' AND categoria = :categoria AND id < :limiter ORDER BY id DESC LIMIT 15";
                $select = DB\Mysql::select(
                    $SELECT,
                    ['categoria' => $this->categoria, 'limiter' => $this->limiter]
                );

            }else{ //Home
                $SELECT = "SELECT * FROM blog WHERE estado = 1 AND id < :limiter ORDER BY id DESC LIMIT 15";
                $select = DB\Mysql::select(
                    $SELECT,
                    ['limiter' => $this->limiter]
                );

            }

        endif;

        if(is_array($select)):
            $data = '';
            foreach ($select as $key => $value):
                $data .= eco('<div class="col-6 col-sm-4 col-md-3" data-id="'.$value["id"].'">');
                $data .= ArtigoData($value);
                $data .= eco('</div>');
            endforeach;
            return $data;
        else:
            return 0;
        endif;
    }

}

if(post('getData')):

    $data = new LoadMore();
    eco($data->getData(), false);
    exit();

endif;

?>