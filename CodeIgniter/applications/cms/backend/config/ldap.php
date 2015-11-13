<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

# LDAP master
$config['ldapmaster']['host']       = '127.0.0.1';
$config['ldapmaster']['port']       = '389';
$config['ldapmaster']['version']    = '17';
$config['ldapmaster']['option']     = '3';
$config['ldapmaster']['base']       = 'dc=teste,dc=br';
$config['ldapmaster']['adminname']  = 'cn=Admin,dc=teste,dc=br';
$config['ldapmaster']['adminpass']  = 'senha_ldap';

# LDAP slave
$config['ldapslave']['host']        = '127.0.0.1';
$config['ldapslave']['port']        = '389';
$config['ldapslave']['version']     = '17';
$config['ldapslave']['option']      = '3';
$config['ldapslave']['base']        = 'dc=teste,dc=br';
$config['ldapslave']['adminname']   = 'cn=Admin,dc=teste,dc=br';
$config['ldapslave']['adminpass']   = 'senha_ldap';
?>
