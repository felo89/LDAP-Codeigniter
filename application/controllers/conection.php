<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Conection extends CI_Controller {
		function index() {
			#cargamos la libreria
			$this->load->library('authentication');
			#instanciamos un objeto authentication
			$adldap = new authentication();
			#llamamos al metodo autenticar el cual responde:
			# en caso de fallar un array de dos valores mensaje y result, dentro de mensaje
			# encontramos el motivo de por que no se pudo conectar y detro de result "false"
			# en caso de tener exito un array con 7 valores 
			# (se deben configurar en el archivo libraries/authentication.php de acuerdo a las necesidades)
			# fullname, apellidos, descripcion, telefono, correo, mensaje y result
			# fullname, apellidos, descripcion, telefono, correo con sus respectivos valores provenientes de active directory
			# mensaje contendrá "Usuario validado correctamente"
			# result contiene "true"
			$user_obj = $adldap->autenticar($username, $password);
			
			$this->load->view('home');
		}
	}
?>