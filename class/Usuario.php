<?php

class Usuario {

	private $idusuario;
	private $deslogin;
	private $dessenha;
	private $dtcadastro;

	public function getIdusuario(){
		return $this->idusuario;
	}
	public function setIdusuario($value){
		$this->idusuario = $value; 
	}

	public function getDeslogin(){
		return $this->deslogin;
	}

	public function setDeslogin($value){
		$this->deslogin = $value;
	}

	public function getDessenha(){
		return $this->dessenha; 
	}

	public function setDessenha($value){
		$this->dessenha = $value; 
	}

	public function getDtcadastro(){
		return $this->dtcadastro;
	}
	public function setDtcadastro($value){
		$this->dtcadastro = $value;
	}

	public function loadById($id){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_usuarios WHERE idusuario = :ID", array(":ID"=>$id));

		if (count($results) > 0) {

			$this->setData($results[0]);
		}
	}

	public function getList(){

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_usuarios ORDER BY deslogin;"); 
	}

	public static function search($login){

		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_usuarios WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(

			':SEARCH'=>"%".$login."%"

		));
	}

	public function login($login, $Password){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_usuarios WHERE deslogin = :LOGIN AND dessenha = :PASSWORD", array(
			":LOGIN"=>$login, 
			":PASSWORD"=>$Password

		));

		if (count($results) > 0) {

			$this->setData($results[0]);

		} else {

			throw new Exception("Login e/ou senha inválidos.");
		}

	}


	public function setData($data){

		$this->setIdusuario($data['idusuario']);
		$this->setDeslogin($data['deslogin']);
		$this->setDessenha($data['dessenha']);
		$this->setDtcadastro(new DateTime($data['dtcadastro']));

	}

	public function insert(){

		$sql = new Sql();

		$results = $sql->select("CALL sp_usuarios_insert(:LOGIN, :PASSWORD)", array(
			":LOGIN"=>$this->getDeslogin(),
			":PASSWORD"=>$this->getDessenha()
		)); 

		if (count($results) > 0){
			$this->setDate($results[0]);
		}

	}

	public function delete(){

		$sql = new Sql();

		$sql->query("DELETE FROM tb_usuarios WHERE idusuario = ID", array(
			':ID'=>$this->getIdusuario()
		));

		$this->setIdusuario(0);
		$this->setDeslogin("");
		$this->setDessenha("");
		$this->setDtcadastro(new DateTime());
	}

	public function __construct($Login = "",$Password = ""){

		$this->setDeslogin($Login);
		$this->setDessenha($Password);
	}

	public function update($Login, $Password){

		$this->setDeslogin($Login);
		$this->setDessenha($Password);

		$sql = new Sql();

		$sql->query("SELECT tb_usuarios SET deslogin = :LOGIN, dessenha = :PASSWORD WHERE idusuario = :ID", array(
			":LOGIN"=>$this->getDeslogin(),
			":PASSWORD"=>$this->getDessenha(),
			":ID"=>$this->getIdusuario() 


		));
	}


	public function __toString(){

		$idusuario = $this->getIdusuario();
		$deslogin = $this->getDeslogin();
		$dessenha = $this->getDessenha();
		$dtcadastro = $this->getDtcadastro();

		if ($dtcadastro != NULL) {

			$dtcadastro = $dtcadastro->format("d/m/Y H:i:s");

		} 

		return json_encode(array(

			"idusuario"=>$idusuario,
			"deslogin"=>$deslogin,
			"dessenha"=>$dessenha,
			"dtcadastro"=>$dtcadastro

		));
		
	}

	
}




?>