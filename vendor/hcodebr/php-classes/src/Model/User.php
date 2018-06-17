<?php 

namespace Hcode\Model;//diretorio da pasta


use \Hcode\DB\Sql;//diretorio a partir da pasta raiz
use \Hcode\Model;

class User extends Model {

	const SESSION = "User";

	public static function login($login, $password)//funcao estatica (::)
	{

		$sql = new Sql;//acessa a classe Sql

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));
		if (count($results) === 0) 
		{// se o  select nao retornar nenhum resultado
				
				throw new \Exception("Usuário inexistente ou senha inválida - msg 1");//estoura uma messagem de erro		
		}

		$data = $results[0];//Primeiro registro encontrado [0]

		if (password_verify($password, $data["despassword"]) === true) 
		{//se a verificacao de pass word for verdadeira
			$user = new User();//criada uma nova instancia da classe User

			$user->setData($data);


			$_SESSION[User::SESSION] = $user->getValues();

			return $user;

		} else {

			throw new \Exception("Usuário inexistente ou senha inválida - Msg 2");		
		}
	}

	public static function verifyLogin($inadmin = true)
	{

		if (
			
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
		) {
			
			header("Location: /admin/login");
			exit;
		}
	}

	public static function logout()
	{
		$_SESSION[User::SESSION] = NULL;
	}

	public static function listAll()
	{

		$sql = new Sql();

		return $sql->select ("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

	}

	public function get($iduser)
	{
 
		 $sql = new Sql();
		 
		 $results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser;", array(
		 ":iduser"=>$iduser
		 ));
		 
		 $data = $results[0];
		 
		 $this->setData($data);
	 
	}

	public function save()
	{

		$sql = new Sql();
		
		$results =  $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", 
			array(
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));


		$this->setData($results[0]);
	}
}
?>
