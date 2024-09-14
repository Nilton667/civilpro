<?php

include_once '../conexao.php';

Class Agenda{

    private $id, $agenda_id, $titulo, $descricao, $data, $checked, $registo;

    function __construct()
    {
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->agenda_id  = post('agenda_id', false) 
        ? filterVar(post('agenda_id'))
        : DEFAULT_INT;
            
        $this->titulo     = post('titulo', false)
        ? filterVar(post('titulo'))  
        : DEFAULT_STRING;

        $this->descricao  = post('descricao', false)
        ? post('descricao')  
        : '';

        $this->data       = post('data', false)
        ? filterVar(post('data'))  
        : '';

        $this->checked    = post('checked', false)
        ? filterInt(post('checked'))  
        : 0;

        $this->registo    = date('d/m/Y');

    }

    function setAgenda() //Criar agenda
    {
        $insert = DB\Mysql::insert(
            'INSERT agenda (titulo, descricao, data, checked, registo) VALUES (:titulo, :descricao, :data, :checked, :registo)',
            [
                'titulo'    => $this->titulo, 
                'descricao' => $this->descricao,
                'data'      => $this->data,
                'checked'   => $this->checked,
                'registo'   => $this->registo
            ]
        );
        return $insert;
    }

    function editAgenda() //Editar anúncio
    {

        $update = DB\Mysql::update(
            'UPDATE agenda SET titulo = :titulo, descricao = :descricao, data = :data, checked = :checked WHERE id = :id',
            [
                'id'        => $this->agenda_id,
                'titulo'    => $this->titulo, 
                'descricao' => $this->descricao,
                'data'      => $this->data,
                'checked'   => $this->checked,
            ]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    function removeData() //Remover evento
    {

        $this->agenda_id = array_map('intval' ,explode(',', $this->agenda_id));
        $key = array_search('', $this->agenda_id);

        if($key!==false){
            unset($this->agenda_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM agenda WHERE id IN(".implode(',', $this->agenda_id).")",
            []
        );
        if (is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }
        
    }

}

if(post('add_agenda')):

    $data = new Agenda();
    eco($data->setAgenda(), true);
    exit();

elseif(post('edit_agenda')):

    $data = new Agenda();
    eco($data->editAgenda(), true);
    exit();

elseif(post('remove_agenda')):

    $data = new Agenda();
    eco($data->removeData(), true);
    exit();

endif;

?>