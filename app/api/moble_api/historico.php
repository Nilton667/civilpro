<?php

include_once 'conexao.php';

class Historico extends Conexao
{
    private $id, $limiter, $ref;

    function __construct(){

        $this->id = isAccessible(
            post('id', false)    ? filterInt(post('id'))    : 0, 
            post('token', false) ? filterVar(post('token')) : ''
        );

        $this->limiter = post('limiter')
        ? filterInt(post('limiter')) 
        : 0;

        $this->ref = post('ref')
        ? filterInt(post('ref')) 
        : 0;

    }

    //Pegar dados das faturas
    function getData(){

        if ($this->limiter <= 0):
            $SELECT = "SELECT * from fatura WHERE id_cliente = :id ORDER BY id DESC LIMIT 15";
        else:
            $SELECT = "SELECT * from fatura WHERE id_cliente = :id AND id < :limiter ORDER BY id DESC LIMIT 15";
        endif;

        $select = DB\Mysql::select(
            $SELECT,
            $this->limiter > 0 
            ? ['limiter' => $this->limiter, 'id' => $this->id]
            : ['id' => $this->id]
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    //Pegar dados por referencias
    function getDataRef(){

        $SELECT = "SELECT * from vendas WHERE id_de_compra = :ref ORDER BY id DESC";

        $select = DB\Mysql::select(
            $SELECT,
            ['ref' => $this->ref]
        );

        if(is_array($select)){
            $total = 0;
            foreach ($select as $key => $value) {
                $selectArtigo = DB\Mysql::select(
                    "SELECT * FROM artigos WHERE id = :id",
                    [
                        'id' => $value['id_objecto']
                    ]
                );
                if(is_array($selectArtigo)):
                    $select[$key]['nome']       = $selectArtigo[0]['nome'];
                    $select[$key]['total']      = $selectArtigo[0]['preco'] * $value['quantidade'].'';
                    $select[$key]['totalCount'] = $selectArtigo[0]['preco'] * $value['quantidade'];
                    $total += $select[$key]['totalCount'];
                    $select[$key]['totalPrice'] = $total.'';
                else:
                    $select[$key]['nome']       = 'n/a';
                    $select[$key]['total']      = '0';
                    $select[$key]['totalCount'] = $selectArtigo[0]['preco'] * $value['quantidade'];
                    $total += $select[$key]['totalCount'];
                    $select[$key]['totalPrice'] = $total.'';
                endif;
            }
            return $select;
        }else{
            return 0;
        }
    }

}

if (post('getData')):

    $data = new Historico();
    eco($data->getData(), true);
    exit();

elseif(post('getDataRef')):

    $data = new Historico();
    eco($data->getDataRef(), true);
    exit();

endif;
    
?>