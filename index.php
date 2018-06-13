<?php 

require_once("vendor/autoload.php");//Tras as dependencias do que o projeto precisa

use \Slim\Slim;//classes carregadas
use \Hcode\Page;//classes carregadas

$app = new Slim();//slim gerencias as rotas da url

$app->config('debug', true);

$app->get('/', function() {//quando chamarem via get a pasta raiz o que esta na rota

	$page = new Page();

	$page->setTpl("index");//carrega o header, body, footer
    
    
	

});

$app->run();//depois que carregar todos os templates, executa

 ?>