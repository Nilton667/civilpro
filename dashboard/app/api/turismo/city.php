<?php

include_once '../conexao.php';

Class City{

    private $id, $city, $descricao, $funcionamento, $hora, $limite, $preco, $old_img, $city_id, $file, $registo, $estado;

    //PASTA
    private $folder = '../../../../publico/img/city/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->city_id       = post('city_id', false) 
        ? filterVar(post('city_id'))  
        : DEFAULT_INT;

        $this->city          = post('city', false)
        ? filterVar(post('city'))  
        : DEFAULT_STRING;
        
        $this->descricao     = post('descricao', false)
        ? trim(post('descricao'))  
        : DEFAULT_STRING;
        
        $this->funcionamento = post('funcionamento', false)
        ? filterVar(post('funcionamento'))  
        : '';

        $this->hora = post('hora', false)
        ? filterVar(post('hora'))  
        : '';

        $this->limite        = is_numeric(post('limite', false))
        ? filterInt(post('limite'), 0)  
        : DEFAULT_INT;

        $this->preco         = is_numeric(post('preco', false)) 
        ? filterInt(post('preco'), 0)  
        : DEFAULT_INT;

        $this->old_img       = post('old_img', false)
        ? filterVar(post('old_img'))  
        : DEFAULT_STRING;

        $this->file          = _file('img');

        $this->registo       = date('d/m/Y');

        $this->estado        = is_numeric(post('estado', false))
        ? filterInt(post('estado'), 0)  
        : DEFAULT_INT;

	}

    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM city WHERE city = :city',
            ['city' => $this->city]
        ))){ return 'Esta cidade/província já se encontra registada!'; }else{ return 0; }
    }

    function setCity() //Criar cidade
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        //FILE INFO
        if ($this->file === false){

            //Publicando sem imagem
            $insert = DB\Mysql::insert(
                'INSERT city (city, descricao, funcionamento, hora, limite, preco, registo, estado) VALUES (:city, :descricao, :funcionamento, :hora, :limite, :preco, :registo, :estado)',
                [
                    'city'          => $this->city, 
                    'descricao'     => $this->descricao, 
                    'preco'         => $this->preco, 
                    'registo'       => $this->registo,
                    'estado'        => $this->estado,
                    'funcionamento' => $this->funcionamento,
                    'hora'          => $this->hora,
                    'limite'        => $this->limite,
                ]
            );

            return $insert;

        }else{
            
            //Publicando com imagem
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );
    
            if(is_array($upload) && isset($upload[0])){
                $insert = DB\Mysql::insert(
                    'INSERT city (city, descricao, funcionamento, hora, limite, imagem, preco, registo, estado) VALUES (:city, :descricao, :funcionamento, :hora, :limite, :img, :preco, :registo, :estado)',
                    [
                        'city'          => $this->city, 
                        'descricao'     => $this->descricao, 
                        'preco'         => $this->preco, 
                        'registo'       => $this->registo,
                        'estado'        => $this->estado,
                        'funcionamento' => $this->funcionamento,
                        'hora'          => $this->hora,
                        'limite'        => $this->limite,
                        'img'           => $upload[0]['name'],
                    ]
                );
                
                return $insert;

            }else{
                return $upload;
            }
            //Final da publicação com imagem

        }

    }

    function editCity() //Editar cidade
    {

        if ($this->file === false){

            //Actualização sem imagem
            $update = DB\Mysql::update(
                'UPDATE city SET city = :city, descricao = :descricao, funcionamento = :funcionamento, hora = :hora, preco = :preco, estado = :estado, limite = :limite WHERE id = :id',
                [
                    'id'            => $this->city_id,
                    'city'          => $this->city, 
                    'descricao'     => $this->descricao, 
                    'preco'         => $this->preco, 
                    'estado'        => $this->estado,
                    'funcionamento' => $this->funcionamento,
                    'hora'          => $this->hora,
                    'limite'        => $this->limite,
                ]
            );

            if(is_numeric($update) && $update > 0){
                return 1;
            }else{
                return $update;
            }

        }else{

            //Actualização com imagem
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );
    
            if(is_array($upload) && isset($upload[0])){
                $update = DB\Mysql::update(
                    'UPDATE city SET city = :city, descricao = :descricao, funcionamento = :funcionamento, hora = :hora, imagem = :img, preco = :preco, estado = :estado, limite = :limite WHERE id = :id',
                    [
                        'id'            => $this->city_id,
                        'city'          => $this->city, 
                        'descricao'     => $this->descricao, 
                        'preco'         => $this->preco, 
                        'estado'        => $this->estado,
                        'funcionamento' => $this->funcionamento,
                        'hora'          => $this->hora,
                        'limite'        => $this->limite,
                        'img'           => $upload[0]['name']
                    ]
                );
                
                if(is_numeric($update) && $update > 0){
                    if(is_file($this->folder.$this->old_img)){ 
                        @unlink($this->folder.$this->old_img); 
                    }
                    return 1;
                }else{
                    return $update;
                }

            }else{
                return $upload;
            }
            //Final da actualização com imagem

        }

    }

    //Remover city
    function removeData(){

        $this->city_id = array_map('intval' ,explode(',', $this->city_id));
        $key = array_search('', $this->city_id);

        if($key!==false){
            unset($this->city_id[$key]);
        }

        $status = 1; 

        foreach ($this->city_id as $key => $value) {
            $select = DB\Mysql::select(
                "SELECT id, imagem FROM city WHERE id = :id",
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM city WHERE id = :id",
                    ['id' => $value]
                );
                if(is_numeric($delete) && $delete > 0){
                    @unlink($this->folder.$select[0]['imagem']);
                }else{
                    $status++;
                    break;  
                }
            }else{
                $status++;
                break;
            }
        }

        return $status;

    }

}

if(post('add_city')):

    $data = new City();
    eco($data->setCity(), true);
    exit();

elseif(post('edit_city')):

    $data = new City();
    eco($data->editCity(), true);
    exit();

elseif(post('remove_city')):

    $data = new City();
    eco($data->removeData(), true);
    exit();

endif;

?>