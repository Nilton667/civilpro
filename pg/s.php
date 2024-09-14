<?php 
    $search    = isset($get_current_page[1]) && $get_current_page[1] != '' 
    ? trim($get_current_page[1]) 
    : '';

    $categoria = isset($get_current_page[2]) ? 
    trim($get_current_page[2]) 
    : '';

    $headerTopic = DB\Mysql::select("SELECT id, categoria FROM categoria", []);
?>

<!-- infinity-search -->
<div id="infinity-search" data-search="<?= $search; ?>" hidden></div>
<!-- /infinity-search -->

<!-- infinity-categoria -->
<div id="infinity-categoria" data-categoria="<?= $categoria; ?>" hidden></div>
<!-- /infinity-categoria -->

<div class="container">
    <br>
    <div class="row">
        <div class="col-12">
            <div>
                <h2 class="red-text">Resultados de Pesquisa para: <?= $search; ?></h2>
            </div>
        </div>
        <div class="col-12 mt-4 mb-4">
            <div class="s-topic">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <?php
                        foreach ($headerTopic as $key => $topic) {
                            ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link <?= $topic['categoria'] == $categoria ? 'active' : ''; ?>" 
                                    id="home-tab" 
                                    href="./s/<?= $search; ?>/<?= $topic['categoria']; ?>" 
                                    role="tab" aria-controls="<?= $topic['categoria']; ?>" 
                                    aria-selected="<?= $topic['categoria'] == $categoria ? 'true' : 'false'; ?>"><b><?= $topic['categoria']; ?></b></a>
                                </li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="container">
        <div class="row" id="infinity-scroll">
            <?php
                if($categoria != ''){
                    $SELECT = "SELECT * FROM blog WHERE estado = 1 AND titulo LIKE '%$search%' AND categoria = :categoria ORDER BY id DESC LIMIT 15";
                    $_posts = DB\Mysql::select(
                        $SELECT,
                        [
                            'categoria' => $categoria
                        ]
                    );
                }else{
                    $SELECT = "SELECT * FROM blog WHERE estado = 1 AND titulo LIKE '%$search%' ORDER BY id DESC LIMIT 15";
                    $_posts = DB\Mysql::select(
                            $SELECT,
                            []
                        );
                }
                if(is_array($_posts)):
                foreach ($_posts as $key => $value) {
                    eco("<div class='col-6 col-sm-4 col-md-4' data-id='".$value['id']."'>");
                        postData($value);
                    eco("</div>");
                }
                else:
                    $removeScroll = true;
                    eco("<p class='lead text-center col-12' style='color: #666;'>Nenhum resultado encontrado!</p>");
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