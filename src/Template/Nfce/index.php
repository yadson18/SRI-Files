<div id='nfce-index' class='container-fluid'>
	<?php if ($nfce): ?>
		<div class='col-sm-12 form-group'>
			<button class='btn btn-success' id='download-zip' disabled>
				Baixar notas selecionadas
				<i class='fas fa-download'></i>
			</button>
		</div>
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
	<?php endif ?>
	<div class='col-sm-12'>
		<?= $this->Paginator->display(); ?>
	</div>
</div>
<script type='text/javascript'>
	$(document).ready(function() {
		function baixaArquivo(dados) {
			if (dados.quantidade && dados.sequenciais && dados.arquivo) {
				if (dados.arquivo.nome && dados.arquivo.tipo) {
					$.ajax({
						url: '/Nfce/download',
		        		xhrFields: { responseType: 'blob' },
		        		data: { 
		        			qtd: dados.quantidade, 
		        			seqs: dados.sequenciais 
		        		},
						method: 'POST'
					})
					.done(function(data, status) {
						//console.log(data);
						//'text/plain'
						//octet//stream
						var url = window.URL.createObjectURL(new Blob([data], {
					    	type: dados.arquivo.tipo
					    }));
						var $a = document.createElement('a');
					    document.body.appendChild($a);

					    $a.download = dados.arquivo.nome;
					    $a.style = 'display: none';
					    $a.href = url;
					    $a.click();

					    window.URL.revokeObjectURL(url);
					});
				}
			}
		}

		var sequenciais = [];

		function filaDownload(acao, sequencial) {
			if (acao === 'adicionar') {
				if (sequenciais.indexOf(sequencial) === -1) {
					sequenciais.push(sequencial);
				}
			}
			else if (acao === 'remover') {
				if (sequenciais.indexOf(sequencial) > -1) {
					sequenciais.splice(sequenciais.indexOf(sequencial), 1);
				}
			}
		}

		$('.download-xml').on('click', function() {
			var nomeArquivo = $(this).attr('id');
			var sequenciais = [$(this).val()];

			baixaArquivo({
				quantidade: 1,  
				sequenciais: sequenciais,
				arquivo: {
					nome: nomeArquivo,
					tipo: 'text/html'
				}
			});
		});


		$('.card-select').on('change', function() {
			if (!$(this).prop('checked')) {
				filaDownload('remover', $(this).val());
			}
			else {
				filaDownload('adicionar', $(this).val());
			}

			if ($('.card-select:checked').length > 1) {
				$('#download-zip').off('click').on('click', function() {
					baixaArquivo({
						quantidade: sequenciais.length,  
						sequenciais: sequenciais,
						arquivo: {
							nome: 'NFCE.zip',
							tipo: 'application/zip'
						}
					});


					//baixaArquivo(sequenciais.length, sequenciais, 'NFCE.zip');
				})
				.prop('disabled', false);
			}
			else {
				$('#download-zip').off('click').prop('disabled', true);
			}
		});


		/*$('#select-notes').on('click', function() {
			$('.card-select').toggleClass('hidden');
			$('#download-selected').toggleClass('hidden');
		});*/

		/*$filesId = {};
		
		

		$('#select-notes').on('click', function() {
			$.ajax({
				url: '/Nfce/download',
				method: 'POST',
        		xhrFields: {
            		responseType: 'blob'
        		},
        		data: { seq: 15 }
			})
			.done(function(data, status) {
				var a = document.createElement('a');
            	var url = window.URL.createObjectURL(data);
            	a.href = url;
            	a.download = 'myfile.pdf';
            	a.click();
            	window.URL.revokeObjectURL(url);

				console.log(data);
			});

			$(this).attr('href', '/Nfce/download/15');
		});*/
	});
</script>