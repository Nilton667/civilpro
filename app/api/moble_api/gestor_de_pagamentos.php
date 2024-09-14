<?php

include_once 'conexao.php';

class Gestor
{
    private $limiter;

    function __construct(){

        $this->limiter        = post('limiter')
        ? filterInt(post('limiter')) 
        : 0;

    }

    function getData(){

        if ($this->limiter <= 0):
            $SELECT = "SELECT * from pagamento ORDER BY id DESC LIMIT 15";
        else:
            $SELECT = "SELECT * from pagamento WHERE id < :limiter ORDER BY id DESC LIMIT 15";
        endif;

        $select = DB\Mysql::select(
            $SELECT,
            $this->limiter > 0 ? ['limiter' => $this->limiter] : []
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

}

if (post('getData')):

    $data = new Gestor();
    eco($data->getData(), true);
    exit();

endif;
    
?>