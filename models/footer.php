<footer>
	<br>
	<div class="container">
		<div class="row mt-4 mb-1">
			<div class="col-12 col-sm-6 col-md-3">
				<br>
				<h5 class="mb-3">ORGANIZAÇÃO</h5>
				<p><i style="font-size: 1.3em;" class="las la-check-circle"></i> 
					<a href="<?= trim(current($get_current_page)) == '' ? '#sobre' : './#sobre'; ?>">Sobre</a>
				</p>
				<p><i style="font-size: 1.3em;" class="las la-bullseye"></i> 
					<a href="<?= trim(current($get_current_page)) == '' ? '#missao' : './#missao'; ?>">Missão</a>
				</p>
				<p><i style="font-size: 1.3em;" class="las la-eye"></i> 
					<a href="<?= trim(current($get_current_page)) == '' ? '#visao' : './#visao'; ?>">Visão</a>
				</p>
				<p><i style="font-size: 1.3em;" class="las la-user-graduate"></i> 
					<a href="<?= trim(current($get_current_page)) == '' ? '#valores' : './#valores'; ?>">Valores</a>
				</p>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<br>
				<h5 class="mb-3">CONTACTO</h5>
				<p>Luanda, Rua Major Cahangulo, Bairro Patrice Lumumba<p>
				<p>Segunda - Sexta (8H / 16H)</p>
				<p>Sábado (8H / 12H)</p>
				<p><a href="mailto:geral@civilpro.co.ao"><i style="font-size: 1.3em;" class="las la-envelope"></i> geral@civilpro.co.ao</a></p>
				<p><a href="tel:+244933006565" style="color: #c5df4a;"><i style="font-size: 1.3em; color: #c5df4a;" class="lab la-whatsapp"></i> (+244) 933 006 565</a></p>
				<p><a href="tel:+244939812572"><i style="font-size: 1.3em;" class="las la-phone"></i> (+244) 939 812 572</a></p>
            </div>
			<div class="col-12 col-sm-6 col-md-3">
				<br>
				<h5 class="mb-3">SERVIÇOS</h5>
				<p><a href="./servicos/#construcao">Construção Civil</a></p>
				<p><a href="./servicos/#fiscalizacao">Fiscalização</a></p>
				<h5 class="mb-3">OUTROS SERVIÇOS</h5>
				<p><a href="./ledyboy">LedyBoy</a></p>
				<p><a href="./tleva">T'Leva</a></p>
				<p><a href="./acabamentos">Acabamentos</a></p>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<br>
				<h5 class="mb-3">NEWSLETTER</h5>
				<p class="text-light mb-3 small">Inscreva-se na nossa newsletter e receba as novidades em primeira mão.</p>
				<div class="form-group">
					<div class="input-group">
					    <input type="email" class="form-control" id="newsletter-email" placeholder="Seu email">
						<div class="input-group-append">
							<button style="height: calc(2.25rem + 10px);" id="newsletter-add" class="btn btn-primary">Adicionar</button>
						</div>						
					</div>
                </div>
                <br>
                <h5>REDES SOCIAIS</h5>
                <div>
				    <a href="https://www.facebook.com/CivilProEngenhariaGestao" target="_blank"><i style="font-size: 3em; color: #2697fb;" class="lab la-facebook"></i></a>
					<a href="https://www.instagram.com/civilpro.ao/" target="_blank"><i style="font-size: 3em; color: #ad61f3;" class="lab la-instagram"></i></a>
					<a href="https://wa.me/244933006565?text=CivilPro%20Lda" target="_blank"><i style="font-size: 3em; color: #c5df4a;" class="lab la-whatsapp"></i></a>
					<!--<a href="" target="_blank"><i style="font-size: 3em; color: #5dccff;" class="lab la-linkedin"></i></a>-->
			    </div>	
			</div>
		</div>
		<br>
		<a href="https://rubro.ao/" target="_blank">
			<img src="publico/img/rubro.png" style="height: 12px;">
		</a>
		<hr>
		<div class="footer">
            <div class="row m-0">
                <div class="col-12 col-md-7 pl-0 p-0">
                    <p>&copy;<?= date('Y'); ?> CivilPro Ltd. Todos os direitos reservados 
                        · <a href="./privacidade">Privacidade</a> 
                        · <a href="./termos">Termos</a>
                    </p>
                </div>
            </div>
		</div>
	</div>
</footer>