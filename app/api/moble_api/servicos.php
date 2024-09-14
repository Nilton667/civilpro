<?php

include_once 'conexao.php';

class Servicos
{
    private $id, $limiter, $categoria, $id_usuario, $id_servico, $data, $hora, $telemovel, $morada, $registo;

    function __construct(){

        $this->id          = post('id')
        ? filterInt(post('id')) 
        : 0;

        $this->limiter     = post('limiter')
        ? filterInt(post('limiter')) 
        : 0;

        $this->categoria   = post('categoria')
        ? filterVar(post('categoria')) 
        : DEFAULT_STRING;

        $this->id_servico  = post('id_servico')
        ? filterVar(post('id_servico')) 
        : 0;

        $this->data        = post('data')
        ? filterVar(post('data')) 
        : DEFAULT_STRING;

        $this->hora        = post('hora')
        ? filterVar(post('hora')) 
        : DEFAULT_STRING;

        $this->telemovel   = post('telemovel')
        ? filterVar(post('telemovel')) 
        : DEFAULT_STRING;

        $this->morada      = post('morada')
        ? filterVar(post('morada')) 
        : DEFAULT_STRING;

        $this->registo     = date('d/m/Y');

    }

    //Pegar dados da categoria
    function getServiceCategoria(){

        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from servicos_categoria ORDER BY id DESC LIMIT 30";
        }else{
            $SELECT = "SELECT * from servicos_categoria WHERE id < :limiter ORDER BY id DESC LIMIT 30";
        }

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

    function getService(){

        if ($this->limiter <= 0) {
            $SELECT = "SELECT * from servicos WHERE categoria = :categoria ORDER BY id DESC LIMIT 15";
        }else{
            $SELECT = "SELECT * from servicos WHERE categoria = :categoria AND id < :limiter ORDER BY id DESC LIMIT 15";
        }

        $select = DB\Mysql::select(
            $SELECT,
            $this->limiter > 0 ? 
                [
                    'limiter' => $this->limiter, 
                    'categoria' => $this->categoria
                ] 
            : 
                [
                    'categoria' => $this->categoria
                ]
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }

    }

    function getServiceId()
    {

        $select = DB\Mysql::select(
            "SELECT * from servicos WHERE id = :id",
            [
                'id' => $this->id
            ]
        );

        if(is_array($select)){
            return $select;
        }else{
            return 0;
        }
    }

    function registar()
    {
        
        $this->id_usuario = isAccessible(
            post('id', false)    ? filterInt(post('id'))    : 0, 
            post('token', false) ? filterVar(post('token')) : ''
        );

        //Controlo de dias
        @$data          = explode('/', $this->data);
        @$ano           = isset($data[2]) ? trim($data[2]) : '2000';
        @$mes           = isset($data[1]) ? trim($data[1]) : '1';
        @$dia           = isset($data[0]) ? trim($data[0]) : '1';
        @$diasRestantes = (int)floor(strtotime($ano.'/'.$mes.'/'.$dia) - strtotime(date('Y/m/d')));
        @$diasRestantes = (int)floor($diasRestantes/(60 * 60 * 24));
        if($diasRestantes < 0): return 'Insira uma data valida!'; endif;

        //Controlo de horas
        @$horas         = explode(':', $this->hora);
        @$hora          = isset($horas[0]) ? trim($horas[0]) : '0';
        $permitir       = ['9', '10', '11', '12', '13', '14', '15', '16', '17'];
        if(!in_array($hora, $permitir)):
            return "Selecione um intervalo de horas das {$permitir[0]} ás {$permitir[count($permitir) - 1]}!";
        endif;

        $insert = DB\Mysql::insert(
            "INSERT INTO servicos_reservas (id_usuario, id_servico, data, hora, telemovel, morada, registo) VALUES (:id_usuario, :id_servico, :data, :hora, :telemovel, :morada, :registo)",
            [
                'id_usuario' => $this->id_usuario,
                'id_servico' => $this->id_servico,
                'data'       => $this->data,
                'hora'       => $this->hora,
                'telemovel'  => $this->telemovel,
                'morada'     => $this->morada,
                'registo'    => $this->registo
            ]
        );

        if(is_numeric($insert) && $insert > 0){
            return 1;
        }else{
            return $insert;
        }
    }

}

if (post('getServiceCategoria')):

    $data = new Servicos();
    eco($data->getServiceCategoria(), true);
    exit();

elseif (post('getService', true)):
    
    $data = new Servicos();
    eco($data->getService(), true);
    exit();

elseif(post('getServiceId')):

    $data = new Servicos();
    eco($data->getServiceId(), true);
    exit();

elseif(post('registar')):

    $data = new Servicos();
    eco($data->registar(), true);
    exit();

endif;
    
?>