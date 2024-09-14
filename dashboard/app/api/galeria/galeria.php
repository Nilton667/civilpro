<?php

include_once '../conexao.php';

Class Galeria{

    private $file, $id, $galeria, $old_galeria, $galeria_id, $localizacao, $localizacao_id, $registo;
    
    //PASTA
    private $folder = '../../../../publico/img/galeria/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->galeria_id   = post('galeria_id', false)
        ? filterVar(post('galeria_id'))  
        : DEFAULT_INT;

        $this->localizacao_id = post('localizacao_id', false)
        ? filterVar(post('localizacao_id'))  
        : DEFAULT_INT;

        $this->galeria      = post('galeria', false)
        ? filterVar(post('galeria'))  
        : DEFAULT_STRING;

        $this->old_galeria  = post('old_galeria', false)
        ? filterVar(post('old_galeria'))  
        : DEFAULT_STRING;

        $this->localizacao  = post('localizacao', false)
        ? filterVar(post('localizacao'))
        : DEFAULT_STRING;

        $this->file         = _file('img');

        $this->registo      = date('d/m/Y');
	}

    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM galeria WHERE pasta = :galeria',
            ['galeria' => $this->galeria]
        ))){ return 'Esta galeria já se encontra registada!'; }else{ return 0; }
    }

    function setGaleria() //Criar galeria
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        $insert = DB\Mysql::insert(
            'INSERT galeria (pasta, registo) VALUES (:galeria, :registo)',
            ['galeria' => $this->galeria, 'registo' => $this->registo]
        );
        return $insert;

    }

    function editGaleria() //Editar galeria
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;
        
        $update = DB\Mysql::update(
            'UPDATE galeria SET pasta = :galeria WHERE id = :id',
            ['id' => $this->galeria_id, 'galeria' => $this->galeria]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover galeria
    {
        $this->galeria_id = array_map('intval' ,explode(',', $this->galeria_id));
        $key = array_search('', $this->galeria_id);

        if($key!==false){
            unset($this->galeria_id[$key]);
        }

        $status = 1; 
        
        foreach ($this->galeria_id as $key => $value) {
            $select = DB\Mysql::select(
                "SELECT id FROM galeria WHERE id = :id",
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM galeria WHERE id = :id",
                    ['id' => $value]
                );
                if(is_numeric($delete) && $delete > 0){
                    $this->deleteImage($value);
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

    function setImage() //Adicionar imagem
    {
        if ($this->file === false):
            return "Selecione no mínimo uma imagem para o diretório!";
        endif;

        $upload = Components\uploadFile::upload(
            $this->file, 
            $this->folder, 
            ['image/png', 'image/jpg', 'image/jpeg'], 
            (1024 * 1024 * 2) // 2MB
        );
        
        if(is_array($upload)){

            foreach ($upload as $key => $value) {
                DB\Mysql::insert(
                    'INSERT galeria_imagem (id_galeria, imagem, localizacao_id, registo) VALUES (:galeria_id, :img, :localizacao_id, :registo)',
                    [
                        'galeria_id'     => $this->galeria_id, 
                        'img'            => $value['name'],
                        'localizacao_id' => $this->localizacao_id,
                        'registo'        => $this->registo,
                    ]
                );
            }

            return 1;

        }else{
            return $upload;
        }
    }

    function removeImage($image) //Remover imagens por seleção
    {

        $delete = DB\Mysql::delete(
            "DELETE FROM galeria_imagem WHERE imagem = :imagem",
            [
                'imagem' => $image
            ]
        );
        
        if(is_numeric($delete) && $delete > 0){
            if(unlink($this->folder.$image)):
                return 1;
            else:
                return 0;
            endif;
        }else{
            return $delete;
        }

    }

    function deleteImage($id) //Remover imagens quando a galeria é deletada
    {

        $select = DB\Mysql::select(
            'SELECT id, imagem FROM galeria_imagem WHERE id_galeria = :id',
            ['id' => $id]
        );

        if(is_array($select)){
            foreach ($select as $key => $value) {
                $delete = DB\Mysql::delete(
                    'DELETE FROM galeria_imagem WHERE id = :id',
                    ['id' => $value['id']]
                );
                if(is_numeric($delete) && $delete > 0){
                    @unlink($this->folder.$value['imagem']);
                }
            }
        }else{
            return $select;
        }

    }

    //Adicionar localização
    function setLocalizacao()
    {
        $insert = DB\Mysql::insert(
            'INSERT galeria_localizacao (nome, registo) VALUES (:nome, :registo)',
            ['nome' => $this->localizacao, 'registo' => $this->registo]
        );
        return $insert;
    }

    //Remover subcategoria
    function removeLocalizacao(){
        
        $this->localizacao_id = array_map('intval' ,explode(',', $this->localizacao_id));
        $key = array_search('', $this->localizacao_id);

        if($key!==false){
            unset($this->localizacao_id[$key]);
        }
        
        $delete = DB\Mysql::delete(
            "DELETE FROM galeria_localizacao WHERE id IN(".implode(',', $this->localizacao_id).")",
            []
        );

        if(is_numeric($delete) && $delete > 0){
            return 1;
        }else{
            return $delete;
        }

    }

    function editLocalizacao() //Editar localizacao
    {
        
        $update = DB\Mysql::update(
            'UPDATE galeria_localizacao SET nome = :nome WHERE id = :id',
            ['id' => $this->localizacao_id, 'nome' => $this->localizacao]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

}

if(post('add_galeria')):

    $data = new Galeria();
    eco($data->setGaleria(), true);
    exit();

elseif(post('edit_galeria')):

    $data = new Galeria();
    eco($data->editGaleria(), true);
    exit();

elseif(post('remove_galeria')):

    $data = new Galeria();
    eco($data->removeData(), true);
    exit();

elseif(post('add_image')):

    $data = new Galeria();
    eco($data->setImage(), true);
    exit();

elseif(post('add_localizacao')):

    $data = new Galeria();
    eco($data->setLocalizacao(), true);
    exit();

elseif(post('remove_localizacao')):

    $data = new Galeria();
    eco($data->removeLocalizacao(), true);
    exit();

elseif(post('edit_localizacao')):

    $data = new Galeria();
    eco($data->editLocalizacao(), true);
    exit();
        
elseif(post('delete_image')):

    $data = new Galeria();
    eco($data->removeImage(filterVar(post('delete_image'))), true);
    exit();

endif;

?>