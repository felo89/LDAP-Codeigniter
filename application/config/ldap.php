<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Desarrollada por Felipe Pincheira Arancibia*/
/*****************************************
*   Ip del servidor de Active Directory  *
*****************************************/
$config['ldap_host'] = "ldap://ip";

/*****************************************
*  DN base en formato dc=foobar, dc=com  *
*****************************************/
$config['base_dn'] = "OU=foobar,DC=com";

/****************************************
*        Dominio ldap del usuario       *
****************************************/
$config['ldap_user_domain'] = "@dominio.com";

/****************************************
* Grupo en el que se buscará al usuario *
****************************************/
$config['ldap_grupo'] = "(memberOf=CN=xxxx,OU=xxxx,OU=Groups,DC=com)";