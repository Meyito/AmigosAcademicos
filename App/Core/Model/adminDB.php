<?php

include_once "Core/Model/model.php";

class AdminDB extends Model{

	public function addMateria($id,$name,$semester){
		$this->connect();
		$query = $this->query("INSERT INTO Materia VALUES ('".$id."','".$name."',".$semester.")");
		$this->terminate();
		return $query;
	}
	public function udpateMateria($id,$name,$semester){
		$this->connect();
		$query = $this->query("UPDATE Materia SET name = '".$name."',semester = '".$semester."' WHERE id = '".$id."' ");
		$this->terminate();
		return $query;
	}
	public function deleteMateria($id){
		$this->connect();
		$query = $this->query("DELETE FROM Materia WHERE id = '".$id."'");
		$this->terminate();
		return $query;
	}
	public function addAmigo($id,$password,$name,$semester,$email,$avatar,$array){
		$this->connect();
		$query = $this->query("INSERT INTO Usuario VALUES ('".$id."','".$name."','".$password."','".$email."',".$semester.",2,'".$avatar."','activo')");
		if($query){
			$iter = 0;
			while($iter<count($array){
				$day = $array[$iter]/10;
				$hour = $array[$iter]%10;
				$query = $this->query("INSERT INTO Agenda VALUES ('".$id."',".$day.",".$hour.")");
				$iter++;
			}
			$this->terminate();
		}
		return $query;	
	}
	
	public function changeStateAmigo($id,$estado){
		$this->connect();
		$query = $this->query("UPDATE Usuario SET estado = '".$estado."' WHERE id = '".$id."' AND tipo = 2");
		$this->terminate();
		return $query;
	}

	
	
}

?>