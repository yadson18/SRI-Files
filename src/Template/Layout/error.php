<!DOCTYPE html>
<html lang="pt-br">
    <head>
    	<title><?= $this->fetch('appName') . $this->fetch('title') ?></title>
        
    	<meta name='viewport' content='width=device-width, initial-scale=1'>
        <?= $this->Html->encoding() ?>

        <?= $this->Html->css('bootstrap.min.css') ?>
        <?= $this->Html->css('fontawesome-all.min.css') ?>
        
        <?= $this->Html->script('jquery.min.js') ?>
        <?= $this->Html->script('bootstrap.min.js') ?>

        <?= $this->Html->less('mixin.less') ?>
        <?= $this->Html->less('error.less') ?>
        <?= $this->Html->script('less.min.js') ?>
    </head>
    <body>
    	<div class='container text-center'>
            <div class='content'> 
                <?= $this->fetch('content') ?>
            </div>
       	</div>
    </body>
</html>