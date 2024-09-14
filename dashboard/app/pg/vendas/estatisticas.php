<div class="content-wrapper-before" style="height: 120px!important;"></div>
<div class="content-header row">
    <div class="content-header-left col-md-4 col-12 mb-2">
        <h3 class="content-header-title mt-2 mb-0">Estatísticas</h3>
    </div>
</div>
<div class="content-body">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                	<div class="container">
                    <div class="heading-elements-ignore d-flex justify-content-end">
                        <ul class="list-inline mb-0">
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        </ul>
                    </div>
                	</div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">                        

                        <div class="container">
							<div class="row justify-content-center">
								<div class="col-12 mt-2 p-0">

                                    <form method="GET" class="mb-2">
                                        <input type="hidden" required="" name="estatisticas">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="data_inicio">Data de inicio</label>
                                                    <input id="data_inicio" type="date" required="" name="filtro" value="<?php if (isset($_GET["filtro"]) && $_GET["filtro"] !="") {echo $_GET["filtro"];} ?>" class="form-control">
                                                </div>            	
                                            </div>

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="data_final">Data final</label>
                                                    <input id="data_final" type="date" required="" name="termino" value="<?php if (isset($_GET["termino"]) && $_GET["termino"] !="") {echo $_GET["termino"];} ?>" class="form-control">
                                                </div>            	
                                            </div>            	
                                        </div>

                                        <div style="text-align: right;">
                                            <button class="btn btn-secondary"><i class="fas fa-search"></i> Filtrar</button>
                                        </div>
                                    </form>             

                                    <div id="container" class="table-responsive">
                                    <?php
                                        $valor_total = 0;

                                            if (isset($_GET["filtro"]) && $_GET["filtro"] !="") {
                                                $busca   = trim(strip_tags($_GET["filtro"]));
                                                $termino = trim(strip_tags($_GET["termino"]));

                                                if (empty($termino)) {
                                                    $termino = trim(strip_tags(date('Y-m-d')));
                                                }

                                                $select = "SELECT * from fatura WHERE STR_TO_DATE(registo, '%d/%m/%Y') BETWEEN '$busca' AND '$termino' ORDER BY id DESC";
                                            }else {
                                                echo "<p style='font-weight: 500;' class='lead'>Vendido Hoje</p>";
                                                $busca  = trim(strip_tags(date('Y-m-d')));
                                                $select = "SELECT * from fatura WHERE STR_TO_DATE(registo, '%d/%m/%Y') BETWEEN '$busca' AND '$busca' ORDER BY id DESC";
                                            }

                                            try {
                                                $result = $conexao->getCon(1)->prepare($select);
                                                $result ->execute();
                                                $contar = $result->rowCount();
                                                if($contar > 0){
                                                    ?>
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">#</th>
                                                                <th scope="col">Referencia</th>
                                                                <th scope="col">Emissão</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                    <?php
                                                    while($mostra = $result->FETCH(PDO::FETCH_OBJ)){
                                                        ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $mostra->id; ?></th>
                                                            <td><?php echo $mostra->id_factura; ?></td>
                                                            <td><?php echo $mostra->registo; ?></td>
                                                            <td>
                                                                <center>
                                                                    <i data-toggle="modal" data-target="#visualizar-fatura<?php echo $mostra->id; ?>" class="las la-eye" data-id></i>							
                                                                </center>
                                                            </td>
                                                        </tr>
<!-- Visualizar factura -->
<div class="modal fade" id="visualizar-fatura<?php echo $mostra->id; ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fatura nº <?php echo $mostra->id; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <p style="font-weight: 500;">Entrega: 
                    <?php 
                        if($mostra->entrega == 0 || $mostra->entrega == "0") {
                            echo "<span style='color: #dc3545;'>Pendente</span>"; 
                        }else{ 
                            echo "<span style='color: #007bff;'>Efetuada</span>"; 
                        }
                    ?>
                </p>
                <div style="border: #ebe9ee 1px solid; border-radius: 5px;" class="mb-1 p-1">
                    <p>
                        <span style="font-weight: 500;">Morada:</span> <?php echo $mostra->morada;  ?>
                    </p>
                    <p class="m-0">
                        <span style="font-weight: 500;">Contacto:</span> 
                        <?= json_decode(getCliente($mostra->id_cliente, 'telemovel'), true);  ?>
                    </p>
                </div>

                <p style="font-weight: 500;">Artigo(s)</p>
                <?php
                    $selectVendas = "SELECT * FROM vendas WHERE id_de_compra = :id ORDER BY id DESC";
                    $resultVendas = $conexao->getCon(1)->prepare($selectVendas);
                    $resultVendas->bindParam(':id', $mostra->id_factura, PDO::PARAM_INT);
                    $resultVendas->execute();
                    $contarVendas = $resultVendas->rowCount();
                    if ($contarVendas > 0) {
                        $totalDeVendas = 0;
                        $inforesult    = $resultVendas->fetchAll();
                        foreach ($inforesult as $key => $value) {

                            $get_name = "SELECT nome from artigos WHERE id = :id_objecto LIMIT 1";
                            $get      = $conexao->getCon(1)->prepare($get_name);
                            $get->bindParam(':id_objecto', $value['id_objecto'], PDO::PARAM_INT);
                            $get->execute();
                            if(($get->rowCount()) > 0):
                                while ($GET = $get->FETCH(PDO::FETCH_OBJ)) {
                                    $inforesult[$key]['name'] = isset($GET->nome) ? $GET->nome : 'n/a';
                                }
                            else:
                                $inforesult[$key]['name'] = 'n/a';
                            endif;
                            ?>
                            <div style="border: #ebe9ee 1px solid; border-radius: 5px;" class="mb-1 p-1">
                                <p><?php echo "<span style='font-weight: 500;'>Descrição:</span> ".$inforesult[$key]['name']; ?></p>
                                <p><?php echo "<span style='font-weight: 500;'>Cor:</span> ".($inforesult[$key]['cor'] != '' ? $inforesult[$key]['cor']  : 'n/a'); ?></p>
                                <p><?php echo "<span style='font-weight: 500;'>Tamanho:</span> ".($inforesult[$key]['tamanho'] != '' ? $inforesult[$key]['tamanho']  : 'n/a'); ?></p>
                                <p><?php echo "<span style='font-weight: 500;'>Preço:</span> ".get_moeda($value['preco']); ?></p>
                                <p><?php echo "<span style='font-weight: 500;'>Quantidade:</span> ".$value['quantidade']; ?></p>
                                <p style="font-weight: 500;" class="text-right">
                                    <?php 
                                        echo "Total: ".get_moeda($value['preco'] * $value['quantidade']);
                                        $totalDeVendas += $value['preco'] * $value['quantidade'];
                                    ?>
                                </p>
                            </div>
                            <?php
                        }
                        ?>
                        <p style="font-weight: 500;" class="text-right">
                            Total: <?= get_moeda($totalDeVendas); ?>
                        </p>

                        <p style="font-weight: 500;" class="text-right">
                            Taxa de entrega: <?= get_moeda($mostra->taxa_deliver); ?>
                        </p>
                        <?php
                            if($mostra->desgnacao_deliver != ''){
                            ?>
                                <p style="font-weight: 500;" class="text-right">
                                    Designação: <?= $mostra->desgnacao_deliver; ?>
                                </p>
                            <?php
                            }
                        ?>
                        <p class="mb-3 text-right red-text">
                            <b>
                                <?php
                                if($mostra->estado == 1){
                                    ?>
                                    <b>Total pago: <?= get_moeda($totalDeVendas + $mostra->taxa_deliver); ?></b>  
                                    <?php
                                }else{
                                    ?>
                                    <b>Total a pagar: <?= get_moeda($totalDeVendas + $mostra->taxa_deliver); ?></b>  
                                    <?php
                                }
                                ?>
                            </b>
                        </p>
                        <?php
                    }else{
                        echo '<p class="text-center lead">Nenhum artigo adicionado!</p>';
                    }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- /Visualizar factura -->
                                                                <?php
                                                            }
                                                            ?>		
                                                        </tbody>
                                                    </table>

                                                    <p style="font-weight: 450;" class="lead text-right mt-2">
                                                        Total de venda(s): <?php echo get_moeda($valor_total); ?>
                                                    </p>
                                                <?php
                                            }else{
                                                echo '<p class="mt-3 text-center lead">Nenhuma venda efetuada!</p>';
                                            }
                                                    
                                        } catch (Exception $e) {
                                            echo '<p class="mt-3 text-center lead">'.$e.'!</p>';     
                                        }
                                    ?>
                                    </div>			
    
								</div>
							</div>
						</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>