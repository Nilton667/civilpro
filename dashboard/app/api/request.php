<?php

include_once 'conexao.php';

class Request
{
    private $filter;

    function __construct()
    {
        $this->filter = post('filter', false)
        ? filterVar(post('filter'))
        : DEFAULT_STRING;
    }

    function getsubcategoria()
    {

        if (post('filter', false)):
            //Continue
        else:
            return 0;
        endif;

        $select = DB\Mysql::select(
            'SELECT subcategoria from subcategoria WHERE categoria = :filter',
            ['filter' => $this->filter]
        );
        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    function getCartStatus()
    {
        $data = [];

        //Pendentes
        $_factura_pendente = DB\Mysql::select(
        "SELECT id FROM fatura WHERE estado = 0"
        );
        if(is_array($_factura_pendente)){
            $data[0] = ['pendentes' => count($_factura_pendente)];
        }else{
            $data[0] = ['pendentes' => ''];
        }

        //Delier
        $_factura_entrega = DB\Mysql::select(
        "SELECT id FROM fatura WHERE estado > 0 AND entrega = 0"
        );
        if(is_array($_factura_entrega)){
            $data[1] = ['deliver' => count($_factura_entrega)];
        }else{
            $data[1] = ['deliver' => ''];
        }
        return $data;
    }
}

if(post('subcategoria')):
    
    $data = new Request();
    eco($data->getsubcategoria(), true);
    exit();

elseif(post('getCartStatus')):
    
    $data = new Request();
    eco($data->getCartStatus(), true);
    exit();

endif;
    
?>