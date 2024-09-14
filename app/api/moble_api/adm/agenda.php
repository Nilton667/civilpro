<?php

include_once '../conexao.php';

class Agenda
{
    private $id, $idArtigo, $limiter;

    function __construct(){

        $this->id = isAccessible(
            post('id', false)    ? filterInt(post('id'))    : 0, 
            post('token', false) ? filterVar(post('token')) : ''
        );

        $this->idArtigo       = post('idArtigo')
        ? filterInt(post('idArtigo')) 
        : 0;

        $this->limiter        = post('limiter')
        ? filterInt(post('limiter')) 
        : 0;

    }

    //Pegar dados do artigo
    function getData(){

        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from servicos_reservas WHERE reservado = :reservado AND concluido = 0 ORDER BY id DESC LIMIT 15";
        }else{
            $SELECT = "SELECT * from servicos_reservas WHERE reservado = :reservado AND concluido = 0 AND id < :limiter ORDER BY id DESC LIMIT 15";
        }

        $select = DB\Mysql::select(
            $SELECT,
            $this->limiter > 0 ?
            ['limiter' => $this->limiter, 'reservado' => $this->id]
            : 
            ['reservado' => $this->id]
        );

        if(is_array($select)){
            foreach ($select as $key => $value) {

                //Serviço
                $selectArtigo = DB\Mysql::select(
                    "SELECT * FROM servicos WHERE id = :id",
                    [
                        'id' => $value['id_servico']
                    ]
                );
                if(is_array($selectArtigo)):
                    $select[$key]['nome']       = $selectArtigo[0]['nome'];
                    $select[$key]['imagem']     = $selectArtigo[0]['imagem'];
                    $select[$key]['preco']      = number_format($selectArtigo[0]['preco'], '2', ',', '.').' AOA';
                    $select[$key]['totalCount'] = $selectArtigo[0]['preco'];
                else:
                    $select[$key]['nome']       = 'n/a';
                    $select[$key]['imagem']     = '';
                    $select[$key]['preco']      = number_format(0, '2', ',', '.').' AOA';
                    $select[$key]['totalCount'] = $selectArtigo[0]['preco'];
                endif;

                //Cliente
                $selectCliente = DB\Mysql::select(
                    "SELECT * FROM usuarios WHERE id = :id",
                    [
                        'id' => $value['id_usuario']
                    ]
                );
                if(is_array($selectCliente)):
                    $select[$key]['cliente']  = $selectCliente[0]['nome'].' '.$selectCliente[0]['sobrenome'];
                    $select[$key]['email']    = $selectCliente[0]['email'];
                else:
                    $select[$key]['cliente']  = 'n/a';
                    $select[$key]['email']    = '';
                endif;

            }
            return $select;
        }else{
            return 0;
        }

    }

    function removeAgenda()
    {

        $update = DB\Mysql::update(
            "UPDATE servicos_reservas SET reservado = :reservado WHERE id = :id",
            [
                'id'        => $this->idArtigo, 
                'reservado' => 0
            ]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return 0;
        } 
    }

}

if (post('getData')):

    $data = new Agenda();
    eco($data->getData(), true);
    exit();

elseif (post('removeAgenda')):

    $data = new Agenda();
    eco($data->removeAgenda(), true);
    exit();

endif;
    
?>