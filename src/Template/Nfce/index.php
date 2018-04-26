<div id='nfce-index' class='container-fluid'>
	<?php if ($nfce): ?>
		<div class='col-sm-12 form-group'>
			<button class='btn btn-primary' id='select-notes'>
				Selecionar notas
			</button>
			<button class='btn btn-success disabled hidden' id='download-selected'>
				Baixar notas selecionadas
				<i class='fas fa-download'></i>
			</button>
		</div>
		<?php foreach ($nfce as $arquivo): ?>
			<div class='card col-md-4 col-sm-6'>
				<div id=<?= $arquivo['seq'] ?> class='card-content'>
					<div class='card-header'>
						<h5>
							<input type='checkbox' class='card-select hidden'>
							<strong>N° da nota:</strong>
							<?= $arquivo['numero_nf'] ?>
						</h5>
						<div class='actions'>
							<a href='#' class='btn btn-info btn-xs'>
								<i class='fas fa-eye'></i>
							</a>
							<a href=/Nfce/download/<?= $arquivo['seq'] ?> target='_blank' class='btn btn-success btn-xs'>
								<i class='fas fa-download'></i>
							</a>
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
	<?php endif ?>
	<div class='col-sm-12'>
		<?= $this->Paginator->display(); ?>
	</div>
</div>
<script type='text/javascript'>
	$(document).ready(function() {
		$('#select-notes').on('click', function() {
			$('.card-select').toggleClass('hidden');
			$('#download-selected').toggleClass('hidden');
		});
	});
</script>