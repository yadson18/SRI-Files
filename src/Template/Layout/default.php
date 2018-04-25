<!DOCTYPE html>
<html lang='pt-br'>
	<head>
		<?= $this->Html->encoding() ?>

		<title><?= $this->fetch('appName') . ' - ' . $this->fetch('title') ?></title>
		<meta name='viewport' content='width=device-width, initial-scale=1'>

		<?= $this->Html->font('Montserrat') ?>

		<?= $this->Html->css('bootstrap.min.css') ?>
		<?= $this->Html->css('fontawesome-all.min.css') ?>
		
		<?= $this->Html->script('jquery.min.js') ?>
		<?= $this->Html->script('bootstrap.min.js') ?>
		<?= $this->Html->script('jquery-mask.min.js') ?>
		<?= $this->Html->script('jquery-datetimepicker.min.js') ?>
		<?= $this->Html->script('internal-functions.js') ?>

		<?= $this->Html->less('mixin.less') ?>
		<?= $this->Html->less('style.less') ?>
		<?= $this->Html->script('less.min.js') ?>
	</head>
	<body>	
		<nav id='main-menu' class='navbar navbar-default'>
  			<div class='container-fluid'>
    			<div class='navbar-header'>
    				<?php if ($this->fetch('controller') !== 'Usuario'): ?>
	      				<button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#menu'>
	        				<span class='icon-bar'></span>
					        <span class='icon-bar'></span>
					        <span class='icon-bar'></span>                        
	      				</button>
	      			<?php endif ?>
      				<a class='navbar-brand' href='#'>SRI</a>
    			</div>
    			<div class='collapse navbar-collapse' id='menu'>
					<?php if ($this->fetch('controller') !== 'Usuario'): ?>
				      	<ul class='nav navbar-nav navbar-right'>
					        <li><a href='#'>ABOUT</a></li>
					        <li><a href='#'>SERVICES</a></li>
					        <li><a href='#'>PORTFOLIO</a></li>
					        <li><a href='#'>PRICING</a></li>
					        <li><a href='#'>CONTACT</a></li>
				      	</ul>
					<?php endif ?>
    			</div>
  			</div>
		</nav>
		<div class='content'>
			<?= $this->fetch('content') ?>
		</div>
	</body>
</html>