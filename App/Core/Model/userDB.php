<?php

include_once "Core/Model/model.php";

class UserDB extends Model{
	
	public function login($id,$password){
		$this->connect();
		$query = $this->query("SELECT * FROM Usuario WHERE id = '".$id."' AND contrasenia = '".$password."' ");
		$this->terminate();
		$cont = 0;
		$user = "";
		while($row = mysqli_fetch_array($query)){
			$cont++;
			$user = $row;
		}
		
		if($cont==1){
			return $user;
		}
		else{
			return false;
		}
	}
	public function isAvailable($id){
		$this->connect();
		$query = $this->query("SELECT * FROM Usuario WHERE id= '".$id."'");
		$this->terminate();
		$iter = 0;
		while($row = mysqli_fetch_array($query)){
			$iter++;
		}
		if($iter<0){
			return true;
		}
		else{
			return false;
		}
		
	}

	public function updateUsuario($id,$password,$name,$semester,$email,$avatar){
		$this->connect();
		$query = $this->query("UPDATE Usuario SET id = '".$id."',nombre = '".$name."',contrasenia = '".$password."',correoElectronico = '".$email."',semestre = ".$semester.",avatar = '".$avatar."' WHERE id='".$id."' ");
		$this->terminate();
		return $query;
	}
	
	public function getAmigos(){
		$this->connect();
		$query = $this->query("SELECT id,nombre,estado FROM Usuario WHERE tipo = 2");
		$this->terminate();
		$array = array();
		while($row = mysqli_fetch_array($query)){
			array_push($array,$row);
		}
		return $array;
	}

	public function getCursos(){
		$this->connect();
		$query = $this->query("SELECT c.id, t.nombre,c.fecha,m.nombre,u.nombre FROM Curso c,Tema t,Materia m,Usuario u 
								WHERE t.id = c.nombre AND m.id = c.idMateria AND u.id = c.idAmigoAcademico 
								ORDER BY c.fecha");
		$this->terminate();
		$array = array();
		while($row = mysqli_fetch_array($query)){
			array_push($array,$row);
		}
		return $array;
	}

	public function getTemasActivos(){
		$this->connect();
		$query = $this->query("SELECT t.id,t.nombre,m.nombre  FROM Tema t,Materia m WHERE t.estado = 1 AND t.idMateria = m.id");
		$this->terminate();
		$array = array();
		while($row = mysqli_fetch_array($query)){
			array_push($array,$row);
		}
		return $array;
	}

}

?>