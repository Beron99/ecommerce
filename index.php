<?php 
session_start();

require_once("vendor/autoload.php");//Tras as dependencias do que o projeto precisa

use \Slim\Slim;//classes carregadas
use \Hcode\Page;//classes carregadas
use \Hcode\PageAdmin;//classes carregadas
use \Hcode\Model\User;//classes carregadas



$app = new Slim();//slim gerencias as rotas da url

$app->config('debug', true);

//Roda do index
$app->get('/', function() {//quando chamarem via get a pasta raiz o que esta na rota

	$page = new Page();

	$page->setTpl("index");//carrega o header, body, footer
   

});

//Roda da page admin
$app->get('/admin', function() {//quando chamarem via get a pasta raiz o que esta na rota
	User::verifyLogin();	

	$page = new PageAdmin();

	$page->setTpl("index");//carrega o header, body, footer
   

});


$app->get('/admin/login', function() {//quando chamarem via get a pasta raiz o que esta na rota

	$page = new PageAdmin([
		"header"=> false,
		"footer"=> false

	]);//nova pagina de PageAdmin

	$page->setTpl("login");//carrega o header, body, footer
   

});


$app->post('/admin/login', function() {//recebe o post da pagina login

	User::login($_POST["login"], $_POST["password"]);//acessa os metodos estaticos de login e password

	header("Location: /admin");//se passar o login abrirá a pagina de admin

	exit;

});

$app->get('/admin/logout', function() {//recebe o post da pagina login

	User::logout();

	header("Location: /admin/login");//se passar o login abrirá a pagina de admin

	exit;

});


$app->run();//depois que carregar todos os templates, executa

 ?>