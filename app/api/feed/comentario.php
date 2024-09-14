<?php

include_once '../conexao.php';

Class Comentario extends Conexao{

    private $id, $id_post, $comentario_id, $user_id, $comment_nome, $comentario, $registo;

	function __construct()
	{
        $this->comentario_id = post('comentario_id', false) 
        ? filterVar(post('comentario_id'))  
        : DEFAULT_INT;

        $this->user_id       = post('user_id', false)
        ? filterVar(post('user_id')) 
        : DEFAULT_INT;
    
        $this->comment_nome  = post('comment_nome', false)
        ? filterVar(post('comment_nome')) 
        : 'Anônimo';

        $this->id_post       = post('id_post', false)
        ? filterVar(post('id_post'))  
        : '';

        $this->comentario    = post('comentario', false)
        ? filterVar(post('comentario'))  
        : '';

        $this->registo       = date('d/m/Y').' ás '.date('H:m');

	}

    function setComent() //Adicionar comentario
    {
        $insert = DB\Mysql::insert(
            'INSERT blog_comentarios (id_post, id_usuario, nome, comentario, registo) VALUES (:id_post, :user_id, :nome, :comentario, :registo)',
            [
                'id_post'    => $this->id_post,
                'user_id'    => $this->user_id,
                'nome'       => $this->comment_nome,
                'comentario' => $this->comentario,
                'registo'    => $this->registo,
            ]
        );
        return json_encode($insert);
    }

    function setSubComent() //Adicionar subcomentario
    {
        $insert = DB\Mysql::insert(
            'INSERT blog_sub_comentarios (id_comentario, id_usuario, nome, comentario, registo) VALUES (:id_comentario, :user_id, :nome, :comentario, :registo)',
            [
                'id_comentario' => $this->comentario_id,
                'user_id'       => $this->user_id,
                'nome'          => $this->comment_nome,
                'comentario'    => $this->comentario,
                'registo'       => $this->registo,
            ]
        );
        return json_encode($insert);
    }

    function removeComent()
    {
        $delete = DB\Mysql::delete(
            'DELETE FROM blog_comentarios WHERE id = :id',
            [
                'id' => $this->comentario_id
            ]
        );

        if(is_numeric($delete) && $delete > 0){
            DB\Mysql::delete(
                'DELETE FROM blog_sub_comentarios WHERE id_comentario = :id_comentario',
                [
                    'id_comentario' => $this->comentario_id
                ]
            );    
            return json_encode(1);
        }else{
            return json_encode('Não foi possível eliminar o seu comentario!');
        }

    }

    function removeSubComent()
    {
        $delete = DB\Mysql::delete(
            'DELETE FROM blog_sub_comentarios WHERE id = :id',
            [
                'id' => $this->comentario_id
            ]
        );

        if(is_numeric($delete) && $delete > 0){
            return json_encode(1);
        }else{
            return json_encode('Não foi possível eliminar o seu comentario!');
        }

    }

    function editComent($target)
    {
        if($target == 'comentario'){
            $query = 'UPDATE blog_comentarios SET comentario = :comentario WHERE id = :id';
        }else{
            $query = 'UPDATE blog_sub_comentarios SET comentario = :comentario WHERE id = :id';
        }

        $update = DB\Mysql::update(
            $query,
            [
                'id'         => $this->comentario_id,
                'comentario' => $this->comentario,
            ]
        );

        if(is_numeric($update) && $update > 0){
            return json_encode(1);
        }else{
            return json_encode(0);
        }
    }

}

if(post('comment_add')):

    $data = new Comentario();
    eco($data->setComent());
    exit();

elseif(post('comment_remove')):
    
    $data = new Comentario();
    eco($data->removeComent());
    exit();

elseif(post('sub_comment_add')):

    $data = new Comentario();
    eco($data->setSubComent());
    exit();

elseif(post('sub_comment_remove')):

    $data = new Comentario();
    eco($data->removeSubComent());
    exit();

elseif(post('edit_comment')):

    $data = new Comentario();
    eco($data->editComent(filterVar(post('edit_comment', false))));
    exit();

endif;

?>