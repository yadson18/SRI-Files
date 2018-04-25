<div id='usuario-login' class='container'>
	 <?= $this->Form->start('', [
	 		'class' => 'col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3'
	 	]) 
	 ?>
	 	<div class='form-header'>
			<h2 class='text-center'>Login</h2>
			<div id='message-box'>
				<?= $this->Flash->showMessage() ?>
			</div>
	 	</div>
	 	<div class='form-body'>
			<div class='form-group icon-right'>
	            <?= $this->Form->input('', [
	                    'placeholder' => 'Digite seu CNPJ',
	                    'class' => 'cnpj form-control',
	                    'autofocus' => true,
	                    'name' => 'cnpj',
	                    'id' => false
	                ]) 
	            ?>
				<i class='fas fa-id-card icon'></i>
			</div>
	        <div class='form-group icon-right'>
	            <?= $this->Form->input('', [
	                    'placeholder' => 'Digite seu usuário',
	                    'class' => 'form-control',
	                    'name' => 'login',
	                    'id' => false
	                ]) 
	            ?>
	            <i class='fas fa-user icon'></i>
	        </div>
	        <div class='form-group icon-right'>
	            <?= $this->Form->input('', [
	                    'placeholder' => 'Digite sua senha',
	                    'class' => 'form-control',
	                    'type' => 'password',
	                    'name' => 'senha',
	                    'id' => false
	                ]) 
	            ?>
	            <i class='fas fa-key icon'></i>
	        </div>
	 	</div>
	 	<div class='form-footer'>
	        <div class='form-group'>
	            <button class='btn btn-success btn-block'>
	                <span>Entrar</span> <i class='fas fa-sign-in-alt'></i>
	            </button>
	        </div>
	 	</div>
	<?= $this->Form->end() ?>
</div>