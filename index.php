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
	User::verifyLogin();//serifica se a pessoa está logada na sessao	

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

$app->get('/admin/users', function() {//tela que lista todos os usurios

	User::verifyLogin();//serifica se a pessoa está logada no sistema	

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(

		"users"=>$users
	));	
		
});

$app->get('/admin/users/create', function() {//tela que lista todos os usurios

	User::verifyLogin();//serifica se a pessoa está logada no sistema	

	$page = new PageAdmin();

	$page->setTpl("users-create");	
		
});

$app->get("/admin/users/:iduser/delete", function($iduser) {//roda de delete pagina com id usuario

	User::verifyLogin();// verifica se usuario está logado no sistema
	
});

$app->get('/admin/users/:iduser', function($iduser){
 
   User::verifyLogin();
 
   $user = new User();
 
   $user->get((int)$iduser);
 
   $page = new PageAdmin();
 
   $page ->setTpl("users-update", array(
        "user"=>$user->getValues()
    ));
 
});

$app->post("/admin/users/create", function() {

	User::verifyLogin();//verifica se o usuario está logado

	$user = new User();

	$_POST["iadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
	exit;
});

$app->post("/admin/users/:iduser", function($iduser) {//roda d uptade por id de usuario

	User::verifyLogin();
	
});





$app->run();//depois que carregar todos os templates, executa

 ?>