<?php

include_once '../conexao.php';

Class Area{

    private $id, $area, $old_area, $area_id, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->area_id   = post('area_id', false) 
        ? filterVar(post('area_id'))  
        : DEFAULT_INT;

        $this->area      = post('area', false)
        ? filterVar(post('area')) 
        : DEFAULT_STRING;

        $this->old_area  = post('old_area', false)
        ? filterVar(post('old_area'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');
	}
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM area_construida WHERE area = :area',
            ['area' => $this->area]
        ))){ return 'Esta área já se encontra registada!'; }else{ return 0; }
    }

    function setarea() //Criar area
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT area_construida (area, registo) VALUES (:area, :registo)',
            ['area' => $this->area, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editarea() //Editar area
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE area_construida SET area = :area WHERE id = :id',
            ['id' => $this->area_id, 'area' => $this->area]
        );
        
        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover area
    {
        
        $this->area_id = array_map('intval' ,explode(',', $this->area_id));
        $key = array_search('', $this->area_id);

        if($key!==false){
            unset($this->area_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM area_construida WHERE id IN(".implode(',', $this->area_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

}

if(post('add_area')):

    $data = new Area();
    eco($data->setArea(), true);
    exit();

elseif(post('edit_area')):

    $data = new Area();
    eco($data->editArea(), true);
    exit();

elseif(post('remove_area')):

    $data = new Area();
    eco($data->removeData(), true);
    exit();

endif;

?>