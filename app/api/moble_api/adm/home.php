<?php

include_once '../conexao.php';

class Home
{
    private $id, $idArtigo, $limiter, $query;

    function __construct(){

        $this->idArtigo       = post('idArtigo')
        ? filterInt(post('idArtigo')) 
        : 0;

        $this->limiter        = post('limiter')
        ? filterInt(post('limiter')) 
        : 0;

        $this->query          = post('query')
        ? filterVar(post('query')) 
        : DEFAULT_STRING;

    }

    //Pegar dados do artigo
    function getData(){

        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from servicos_reservas WHERE pago = 0 AND reservado = 0 ORDER BY id DESC LIMIT 15";
        }else{
            $SELECT = "SELECT * from servicos_reservas WHERE pago = 0 AND reservado = 0 AND id < :limiter ORDER BY id DESC LIMIT 15";
        }

        $select = DB\Mysql::select(
            $SELECT,
            [
               'limiter' => $this->limiter
            ]
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

    function getSearchData()
    {
        $SELECT = "SELECT nome from servicos WHERE nome LIKE '%$this->query%'";

        $select = DB\Mysql::select(
            $SELECT,
            []
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    function getQueryData()
    {
        $id_servico    = '';
        $selectService = DB\Mysql::select(
            'SELECT id FROM servicos WHERE nome = :nome',
            ['nome' => $this->query]
        ); $id_servico = is_array($selectService) ? $selectService[0]['id'] : 0;

        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from servicos_reservas WHERE pago = 0 AND reservado = 0 AND id_servico = $id_servico ORDER BY id DESC LIMIT 15";
        }else{
            $SELECT = "SELECT * from servicos_reservas WHERE pago = 0 AND reservado = 0 AND id_servico = $id_servico AND id < :limiter ORDER BY id DESC LIMIT 15";
        }

        $select = DB\Mysql::select(
            $SELECT,
            $this->limiter > 0
            ? ['limiter' => $this->limiter]
            : []
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

    function addAgenda()
    {
        $this->id = isAccessible(
            post('id', false)    ? filterInt(post('id'))    : 0, 
            post('token', false) ? filterVar(post('token')) : ''
        );

        $select = DB\Mysql::select(
            "SELECT id FROM servicos_reservas WHERE id = :id AND reservado != :reservado",
            ['id' => $this->idArtigo, 'reservado' => 0]
        );

        if(is_array($select)){
            return 'Serviço indisponível!';
        }

        $update = DB\Mysql::update(
            "UPDATE servicos_reservas SET reservado = :reservado WHERE id = :id",
            ['id' => $this->idArtigo, 'reservado' => $this->id]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return 0;
        } 
    }

}

if (post('getData')):

    $data = new Home();
    eco($data->getData(), true);
    exit();

elseif(post('getSearchData')):

    $data = new Home();
    eco($data->getSearchData(), true);
    exit();

elseif(post('getQueryData')):

    $data = new Home();
    eco($data->getQueryData(), true);
    exit();

elseif (post('addAgenda')):

    $data = new Home();
    eco($data->addAgenda(), true);
    exit();

endif;
    
?>