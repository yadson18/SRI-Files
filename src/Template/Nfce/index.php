<div id='nfce-index' class='container-fluid'>
	<div class='col-sm-12 form-group'>
		<div class='pull-right form-group'>
			<button class='btn btn-primary' id='mark-all'>
				Marcar todas
				<i class='fas fa-check'></i>
			</button>
			<button class='btn btn-danger' id='unmark-all'>
				Desmarcar todas
				<i class='fas fa-times'></i>
			</button>
			<button class='btn btn-success' id='download-zip' disabled>
				Baixar notas selecionadas
				<i class='fas fa-download'></i>
			</button>
		</div>
		<div>
			<form class='form-inline'>
				<div class='form-group'>
					<label>Filtro <i class='fas fa-filter'></i>: </label>
				</div>
				<div class='form-group'>
					&nbsp;
					<label>Data inicial</label> 
					<input class='form-control date' placeholder='EX:10/01/2018' id='inicio'>
				</div>
				<div class='form-group'>
					&nbsp;
					<label>Data final</label> 
					<input class='form-control date' placeholder=EX:<?= date('d/m/Y') ?> id='fim'>
				</div>
				<div class='form-group'>
					<button class='btn btn-primary' type='button' id='find'>
						<i class='fas fa-search'></i>
					</button>
				</div>
			</form>
		</div>
	</div>
	<?php if ($nfce): ?>
		<?php foreach ($nfce as $arquivo): ?>
			<div class='card col-md-4 col-sm-6'>
				<div id=<?= $arquivo['seq'] ?> class='card-content'>
					<div class='card-header'>
						<h5>
							<input type='checkbox' value=<?= $arquivo['seq'] ?> class='card-select'>
							<strong>N° da nota:</strong>
							<?= $arquivo['numero_nf'] ?>
						</h5>
						<div class='actions'>
							<a href='#' class='btn btn-info btn-xs'>
								<i class='fas fa-eye'></i>
							</a>
							<button id=<?= $arquivo['chave'] ?>-NFCE.XML value=<?= $arquivo['seq'] ?> class='btn btn-success btn-xs download-xml'>
								<i class='fas fa-download'></i>
							</button>
						</div>
					</div>
					<div class='card-body'>
						<ul class='list-group'>
							<li class='list-group-item'>
						    	<strong>Modelo</strong>
						    	<span class='badge'>
						    		<?= $arquivo['modelo'] ?>
						    	</span>
						  	</li>
						  	<li class='list-group-item'>
						    	<strong>Série</strong>
						    	<span class='badge'>
						    		<?= $arquivo['serie'] ?>
						    	</span>
						  	</li>
						  	<li class='list-group-item'>
						    	<strong>Data da autorização</strong>
						    	<span class='badge'>
						    		<?= date('d/m/Y', strtotime($arquivo['data_evento'])) ?>
						    	</span>
						  	</li>
						  	<li class='list-group-item'>
						    	<strong>Hora da autorização</strong>
						    	<span class='badge'>
						    		<?= date('H:i:s', strtotime($arquivo['hora_evento'])) ?>
						    	</span>
						  	</li>
							<li class='list-group-item'>
						    	<strong>Status</strong>
						    	<span class='badge'>
						    		<?= $arquivo['status'] ?>
						    	</span>
						  	</li>
						</ul>
					</div>
				</div>
			</div>
		<?php endforeach ?>
	<?php else: ?>
		<div id='nothing-found' class='text-center'>
			<h3>
				<i class='far fa-frown'></i> Nenhuma nota foi encontrada.
			</h3>
			<a href='/Nfce/index' class='btn btn-primary'>
				<i class='fas fa-angle-double-left'></i> Clique aqui para retornar
			</a>
		</div>
	<?php endif ?>
	<div class='col-sm-12'>
		<?= $this->Paginator->display(); ?>
	</div>
</div>