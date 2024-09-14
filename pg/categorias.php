<div class="container">
    <br>
    <div class="row">
        <div class="col-12 col-sm-6">
            <center>
                <img src="publico/img/categorias/categorias.png" class="img-fluid" style="max-width: 100%; width: 450px;">
            </center>
        </div>
        <div class="col-12 col-sm-6 d-flex align-items-center">
            <div>
                <h2>Categorias</h2>
            </div>
        </div>
    </div>
    <br>
</div>

<div class="container">
    <div class="row">
        <?php
            $topic = DB\Mysql::select(
                "SELECT * FROM categoria",
                []
            );
            if(is_array($topic)):
            foreach ($topic as $key => $value) {
                $imagem_ = is_file("./publico/img/categorias/{$value['imagem']}") 
                ? "./publico/img/categorias/{$value['imagem']}" 
                : "./publico/img/categorias/default.png";
                ?>
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <a href="./categoria/<?= $value['categoria']; ?>">
                            <div style="color: #fff; background: url('<?= $imagem_; ?>'); height: calc(100vh / 3); background-size: cover; background-position: center; display: flex; justify-content: center; align-items: center;">
                                <h3 style="text-shadow: 6px 6px 6px rgba(0, 0, 0, 0.4); color: #fff;" 
                                class="p-2 text-center"><b><?= $value['categoria']; ?></b></h3>
                            </div>
                        </a>
                    </div>
                <?php
            }
            endif;
        ?>
    </div>
</div>