<?php

	include_once "Core/Model/userDB.php";
	include_once "Core/Model/studentDB.php";

	class Controller{

		/**
		* Metodo que toma el archivo estatico de la pagina inicial y lo carga en pantalla
		*/
		public function index(){
			$index = $this->getTemplate("Core/View/index.html");
			$this->showView($index);
		}

		/**
		* Metodo que carga un archivo de la vista
		* @param $route - Ruta del archivo a cargar
		* @return string con el valor html que debe ser mostrado
		*/
		public function getTemplate($route){
			return file_get_contents($route);
		}
		
		/**
		*	Toma una vista y la muestra en pantalla en el cliente
		* 	@param $vista - vista preconstruida para mostrar en el navegador
		*/
		public function showView($view){
			echo $view;
		}

		/**
		*	Reemplaza un valor por otro en una cadena de texto. Utilizado para formatear las vistas
		* 	@param $ubicacion - String donde se reemplazará el valor
		* 	@param $cadenaReemplazar - Cadena que será buscada en la $ubicación
		*	@param $reemplazo - Cadena con la que se reemplazará $cadenaReemplazar
		*	@return cadena sobreescrita
		*/
		public function renderView($ubicacion, $cadenaReemplazar, $reemplazo){
			return str_replace($cadenaReemplazar, $reemplazo, $ubicacion);
		}

		public function login($name, $password){
			$password=$this->encryptPassword($password);

			$userModel=new UserDB();
			//$data=$userModel->login($name, $pass);
			$data=$userModel->login($name, $password);

			//print_r($data);
			if($data!=false){
				//cargar los datos de sesion y de acuerdo al usuario cargar la vista asociada.
				$this->setSession($data);
				if($data["tipo"]==1){
					$_SESSION["tipo"]="Administrador";
					header('Location: index.php');
				}else if($data["tipo"]==2){
					$_SESSION["tipo"]="Amigo Académico";
					header('Location: index.php');
				}else{
					$_SESSION["tipo"]="Estudiante";
					header('Location: index.php');
				}
			}else{
				//ENVIAR ALERTA DE ERROR
				$this->index();
			}

		}

		public function encryptPassword($pass){
			return sha1($pass);
		}

		public function setSession($data){
			$_SESSION["codigo"]=$data["id"];
			$_SESSION["nombre"]=$data["nombre"];
			$_SESSION["avatar"]=$data["avatar"];
		}

		public function base($route){
			$template = $this->getTemplate("Core/View/base.html");
			$template = $this->renderView($template, "{{BASICO:NOMBRE}}", $_SESSION["nombre"]);
			$template = $this->renderView($template, "{{BASICO:TIPO_USUARIO}}", $_SESSION["tipo"]);
			$template = $this->renderView($template, "{{BASICO:AVATAR}}", $_SESSION["avatar"]);
			$menu=$this->getTemplate($route);
			$template = $this->renderView($template, "{{CICLO:ITEM_SIDEBAR}}", $menu);
			return $template;
		}

		public function logout(){
			$_SESSION["nombre"] = false;
			$_SESSION["tipo"] = false;
			session_destroy();
			header('location:index.php');
		}

		public function studentRegister($codigo, $nombre, $correo, $semestre, $password){
			$password=$this->encryptPassword($password);
			$studentModel=new StudentDB();
			$avatar="Static/img/avatars/h1.png";
			$rta=$studentModel->addStudent($codigo,$password,$nombre,$semestre,$correo,$avatar);
			$this->login($codigo, $password);
		}
	}

?>