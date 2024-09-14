<?php

include_once '../conexao.php';

Class Deliver{

    private $id, $localizacao, $latitude, $longitude, $preco, $registo;

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->localizacao_id   = post('localizacao_id', false) 
        ? filterVar(post('localizacao_id'))  
        : DEFAULT_INT;

		$this->localizacao = post('localizacao', false)
		? filterVar(post('localizacao'))
		: DEFAULT_STRING; 

		$this->latitude = post('latitude', false)
		? filterVar(post('latitude'))
		: DEFAULT_INT;

		$this->longitude = post('longitude', false)
		? filterVar(post('longitude'))
		: DEFAULT_INT;

		$this->preco = post('preco', false)
		? filterInt(post('preco'))
		: DEFAULT_INT;

        $this->registo = date('d/m/Y');
	}

    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM deliver WHERE localizacao = :localizacao AND preco = :preco AND latitude = :latitude AND longitude = :longitude',
            [
				'localizacao' => $this->localizacao,
                'preco'       => $this->preco,
                'latitude'    => $this->latitude,
                'longitude'   => $this->longitude
			]
        ))){ return 'Esta localização já se encontra registada!'; }else{ return 0; }
    }

    function setDeliver() //Criar deliver
    {
        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT deliver (localizacao, latitude, longitude, preco, registo) VALUES (:localizacao, :latitude, :longitude, :preco, :registo)',
            [
				'localizacao' => $this->localizacao,
				'latitude'    => $this->latitude, 
				'longitude'   => $this->longitude, 
				'preco'       => $this->preco,  
				'registo'     => $this->registo
			]
        );
        return $insert;
    }

    function editDeliver() //Editar deliver
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $update = DB\Mysql::update(
            'UPDATE deliver SET localizacao = :localizacao, latitude = :latitude, longitude = :longitude, preco = :preco WHERE id = :id',
            [
				'id'          => $this->localizacao_id, 
				'localizacao' => $this->localizacao,
				'latitude'    => $this->latitude, 
				'longitude'   => $this->longitude, 
				'preco'       => $this->preco
			]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    //Remover cor
    function removeData(){

        $this->localizacao_id = array_map('intval' ,explode(',', $this->localizacao_id));
        $key = array_search('', $this->localizacao_id);

        if($key!==false){
            unset($this->localizacao_id[$key]);
        }

        $delete = DB\Mysql::delete(
            "DELETE FROM deliver WHERE id IN(".implode(',', $this->localizacao_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }
        
    }

}

if(post('add_deliver')):

    $data = new Deliver();
    eco($data->setDeliver(), true);
    exit();

elseif(post('edit_deliver')):

    $data = new Deliver();
    eco($data->editDeliver(), true);
    exit();

elseif(post('remove_deliver')):

    $data = new Deliver();
    eco($data->removeData(), true);
    exit();

endif;

?>