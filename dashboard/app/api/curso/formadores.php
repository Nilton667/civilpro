<?php

include_once '../conexao.php';

Class Formadores{

    private $id, $nome, $imagem, $old_image, $formador_id, $descricao, $file, $registo;
    
    //PASTA
    private $folder   = '../../../../publico/img/formadores/';

	function __construct()
	{
        $this->id = Components\getSession('maestro_adm', 'id', 1,  true);

        $this->formador_id  = post('formador_id', false)
        ? filterVar(post('formador_id'))  
        : DEFAULT_INT;

        $this->nome         = post('nome', false)
        ? filterVar(post('nome'))  
        : DEFAULT_STRING;

        $this->descricao    = post('descricao', false) 
        ? trim(post('descricao'))  
        : '';
        
        $this->old_image      = post('old_image', false)
        ? filterVar(post('old_image'))  
        : DEFAULT_STRING;

        $this->registo = date('d/m/Y');

        $this->file      = _file('img');
	}
    
    function verificacao(){
        if(is_array(DB\Mysql::select(
            'SELECT id FROM cursos_formadores WHERE nome = :nome AND descricao = :descricao',
            [
                'nome'      => $this->nome, 
                'descricao' => $this->descricao
            ]
        ))){ 
            return 'Este formador já se encontra registado!'; 
        }else{ 
            return 0; 
        }
    }

    function setFormador() //Criar formador
    {

        $verificacao     = $this->verificacao();
        if($verificacao !== 0): return $verificacao; endif;

        if ($this->file === false){
            $this->imagem = '';
        }else{
            //Adicionando imagem ao formador
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );

            if(is_array($upload) && isset($upload[0])){
                $this->imagem = $upload[0]['name'];
            }else{
                return $upload;
            }
        }

        $insert = DB\Mysql::insert(
            'INSERT cursos_formadores (nome, imagem, descricao, registo) VALUES (:nome, :imagem, :descricao, :registo)',
            [
                'nome'      => $this->nome,
                'imagem'    => $this->imagem,
                'descricao' => $this->descricao, 
                'registo'   => $this->registo
            ]
        );
        return $insert;

    }

    function editFormador() //Editar formador
    {
        
        if ($this->file === false){
            $this->imagem = $this->old_image;
        }else{
            //Adicionando imagem ao formador
            $upload = Components\uploadFile::upload(
                $this->file, 
                $this->folder, 
                ['image/png', 'image/jpg', 'image/jpeg'], 
                (1024 * 1024 * 2) // 2MB
            );

            if(is_array($upload) && isset($upload[0])){
                @unlink($this->folder.$this->old_image);
                $this->imagem = $upload[0]['name'];
            }else{
                return $upload;
            }
        }

        $update = DB\Mysql::update(
            'UPDATE cursos_formadores SET nome = :nome, imagem = :imagem, descricao = :descricao WHERE id = :id',
            [
                'id'        => $this->formador_id, 
                'nome'      => $this->nome,
                'imagem'    => $this->imagem, 
                'descricao' => $this->descricao
            ]
        );

        if(is_numeric($update) && $update > 0){
            return 1;
        }else{
            return $update;
        }

    }

    
    function removeData() //Remover formador
    {
        $this->formador_id = array_map('intval' ,explode(',', $this->formador_id));
        $key = array_search('', $this->formador_id);

        if($key!==false){
            unset($this->formador_id[$key]);
        }

        $status = 1; 

        foreach ($this->formador_id as $key => $value){
            $select = DB\Mysql::select(
                'SELECT id, imagem FROM cursos_formadores WHERE id = :id',
                ['id' => $value]
            );
            if(is_array($select)){
                $delete = DB\Mysql::delete(
                    "DELETE FROM cursos_formadores WHERE id = :id",
                    ['id' => $value]
                );
                if($delete <= 0){
                    $status = 0;
                    break;
                }else{
                    @unlink($this->folder.$select[0]['imagem']);
                }
            }else{
                $status++;
                break;
            }
        }

        return $status;

    }

}

if(post('add_formador')):

    $data = new Formadores();
    eco($data->setFormador(), true);
    exit();

elseif(post('edit_formador')):

    $data = new Formadores();
    eco($data->editFormador(), true);
    exit();

elseif(post('remove_formador')):

    $data = new Formadores();
    eco($data->removeData(), true);
    exit();

endif;

?>