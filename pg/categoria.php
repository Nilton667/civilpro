<?php 
    $categoria = isset($get_current_page[1]) && trim($get_current_page[1]) != '' 
    ? trim($get_current_page[1]) 
    : ''; 

    $subcategoria = isset($get_current_page[2]) && trim($get_current_page[2]) != '' 
    ? trim($get_current_page[2]) 
    : ''; 
?>

<!-- infinity-categoria -->
<div id="infinity-categoria" data-categoria="<?= $categoria; ?>" hidden></div>

<!-- infinity-subcategoria -->
<div id="infinity-subcategoria" data-subcategoria="<?= $subcategoria; ?>" hidden></div>

<style>
    .carousel.categoria img{
        height: calc(100vh / 2)!important;
        object-fit: cover;
        object-position: center;
        cursor: pointer;
        transition: opacity 0.5s;
    }
    .carousel.categoria img:hover{
        opacity: 0.7;
    }
    @media screen and (max-width: 500px){
        .cat-data{
            font-size: 1.1em;
        }
    }
</style>

<?php

    $categoria_img = DB\Mysql::select(
        "SELECT imagem, descricao FROM categoria WHERE categoria = :categoria LIMIT 1",
        [
            'categoria' => $categoria
        ]
    );
    if(is_array($categoria_img)){
        $categoria_descricao = $categoria_img[0]['descricao'];
    }
    $categoria_img = is_array($categoria_img) && is_file("./publico/img/categorias/{$categoria_img[0]['imagem']}") ? "./publico/img/categorias/{$categoria_img[0]['imagem']}" : "./publico/img/categorias/default.png";
?>

<div class="mb-4 container">
    <div style="color: #fff; background: url('<?= $categoria_img; ?>') no-repeat; height: calc(100vh / 2); background-size: cover; background-position: center; display: flex; justify-content: center; align-items: center;">
        <div class="container">
            <h2 class="p-2 text-center" style="color: #fff; text-shadow: 6px 6px 12px rgba(0, 0, 0, 0.8);">
                <b><?= $categoria == '' ? 'Todas categorias' : $categoria; ?></b>
            </h2> 
            <?php 
                if(isset($categoria_descricao)){
                    ?>
                    <p 
                    style="text-shadow: 6px 6px 6px rgba(0, 0, 0, 0.4);" 
                    class="cat-data text-center lead pl-2 pr-2"><?= $categoria_descricao; ?></p>
                    <?php
                }
            ?>           
        </div>
    </div>
</div>

<div class="container mt-3 mb-3">
    <?php
        $subcategorias = DB\Mysql::select("SELECT * FROM subcategoria WHERE categoria = :categoria", 
            [
                'categoria' => $categoria
            ]
        );
        if(is_array($subcategorias)){
            ?>
                <br>
                    <h3 class="mb-2" style="color: #666;"><b>Subcategorias</b></h3>
                <br>
            <?php
        }
    ?>
    <div class="row">
        <div class="col-12">
            <div class="s-topic">
            <?php
                if(is_array($subcategorias)){
                    ?>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <?php
                                foreach ($subcategorias as $key => $value) {
                                    if($key == 0){
                                        ?>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link <?= $subcategoria == "" ? 'active' : ''; ?>" 
                                            id="home-tab" 
                                            href="./categoria/<?= $categoria; ?>/" 
                                            role="tab" aria-controls="<?= $subcategoria; ?>" 
                                            aria-selected="<?= $subcategoria == "" ? 'true' : 'false'; ?>"><b>Mostrar todos</b></a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link <?= $value['subcategoria'] == $subcategoria ? 'active' : ''; ?>" 
                                            id="home-tab" 
                                            href="./categoria/<?= $categoria; ?>/<?= $value['subcategoria']; ?>" 
                                            role="tab" aria-controls="<?= $value['subcategoria']; ?>" 
                                            aria-selected="<?= $value['subcategoria'] == $subcategoria ? 'true' : 'false'; ?>"><b><?= $value['subcategoria']; ?></b></a>
                                        </li>
                                    <?php
                                }
                            ?>
                        </ul>
                    <?php
                }
            ?>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="container">
        <div class="row" id="infinity-scroll">
            <?php
                if($categoria == ''){
                    $_posts = DB\Mysql::select(
                        "SELECT * FROM blog WHERE estado = 1 ORDER BY id DESC LIMIT 15",
                        []
                    );
                }else{
                    $_posts = DB\Mysql::select(
                        "SELECT * FROM blog WHERE estado = 1 AND categoria = :categoria AND subcategoria LIKE '%$subcategoria%' ORDER BY id DESC LIMIT 15",
                        ['categoria' => $categoria]
                    );
                }
                if(is_array($_posts)):
                foreach ($_posts as $key => $value) {
                    eco("<div class='col-6 col-sm-4 col-md-3' data-id='".$value['id']."'>");
                        postData($value);
                    eco("</div>");
                }
                else:
                    $removeScroll = true;
                    eco("<p class='lead text-center col-12'>Nenhum resultado encontrado!</p>");
                endif;
            ?>
        </div>
    </div>
</div>

<?php
    if(!isset($removeScroll)){
        ?>
        <div class="p-4 text-center" id="infinity-load" hidden>
            <div class="spinner-grow text-danger" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <?php
    }
?>