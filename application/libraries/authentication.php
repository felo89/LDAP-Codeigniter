<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* Desarrollada por Felipe Pincheira Arancibia*/
	class authentication extends CI_Controller{

		/*********************************************************************************
		* Funcion que sirve para autenticar un usuario con active directory              *
		* recive como parametros el usuario y la contraseña del usuario a autenticar     *
		* retorna:                                                                       *
		* -un objeto con un mensaje y un resultado en caso de no existir                 *
		* -un objeto con un mensaje, resultado y los datos del usuario en caso contrario *
		* mensaje: contiene un mensaje generico para saber que ocurrió                   *
		* resultado: es un campo llamado result que contiene true en caso de exito y     *
		* false en caso de error                                                         *
		*********************************************************************************/
		public function autenticar($username, $password) {
			//cargamos el archivo de configuracion de ldap
			$this->load->config('ldap');
			//creamos el objeto que retornaremos
			$obj_usuario = array();

			//si el usuario o contraseña estan vacios, se devuelve un mensaje
			if($username == '' || $password == ''){
				$obj_usuario['mensaje'] = "Usuario y password son requeridos";
				$obj_usuario['result'] = false;

				return $obj_usuario;
			}else{
				//recuperamos los datos de configuracion
				$ldap_host = $this->config->item('ldap_host');
				$base_dn = $this->config->item('base_dn');
				$ldap_usr_dom = $this->config->item('ldap_user_domain');
				$ldap_grupo = $this->config->item('ldap_grupo');

				//creamos la conexion de ldap
				$ldap = ldap_connect($ldap_host);
				ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION,3);
				ldap_set_option($ldap, LDAP_OPT_REFERRALS,0);
				//validamos si el usuario existe en active directory
				$ldapBind = @ldap_bind($ldap, $username.$ldap_usr_dom, $password);
				//si el usuario no existe retornamos mensaje
				if(!$ldapBind){
					$obj_usuario['mensaje'] = "Usuario y/o password invalidos";
					$obj_usuario['result'] = false;

					ldap_unbind($ldap);
					return $obj_usuario;
				}else{
					$filter = "(&(objectClass=user) (samaccountname=".$username.") ".$ldap_grupo.")";
					//buscamos el usuario en el grupo de active directory
					$sr = ldap_search($ldap, $base_dn, $filter);
					//devolvemos los datos del usuario
					$usu = ldap_get_entries($ldap, $sr);
					if ($usu["count"] > 0){
						//guardamos los datos en el aray que se retorna
						//configurar el array $usu[0]["cn"][0] de acuerdo a lo que devuelva el active directory
						$obj_usuario['fullname'] = isset($usu[0]["cn"][0]) ? $usu[0]["cn"][0] : "";
						$obj_usuario['apellidos'] = isset($usu[0]["sn"][0]) ? $usu[0]["sn"][0] : "";
						$obj_usuario['descripcion'] = isset($usu[0]["description"][0]) ? $usu[0]["description"][0] : "";
						$obj_usuario['telefono'] = isset($usu[0]["telephonenumber"][0]) ? $usu[0]["telephonenumber"][0] : "";
						$obj_usuario['correo'] = isset($usu[0]["mail"][0]) ? $usu[0]["mail"][0] : "";
						$obj_usuario['mensaje'] = "Usuario validado correctamente";
						$obj_usuario['result'] = true;

						ldap_unbind($ldap);
						return $obj_usuario;
					}else{
						$obj_usuario['mensaje'] = "No se pudo obtener el usuario";
						$obj_usuario['result'] = false;

						ldap_unbind($ldap);
						return $obj_usuario;
					}
				}
			}
		}
	}
?>