<?php
class MY_Ldap
{
    public function autentica($user, $pass, $objectClass='univates')
    {
           $CI =& get_instance();
        $CI->load->config('ldap');

        // Tenta conectar no LDAP master
        $ldapConfig = $CI->config->config['ldapacadmaster'];
        $conn = ldap_connect($ldapConfig['host'], $ldapConfig['port']);
        ldap_set_option($conn, $ldapConfig['version'], $ldapConfig['option']);
        $r=ldap_bind($conn, $ldapConfig['adminname'],$ldapConfig['adminpass']);
        if (!$r)
        {
            // Tenta conectar no LDAP slave
            $ldapConfig = $CI->config->config['ldapacadslave'];
            $conn = ldap_connect($ldapConfig['host'], $ldapConfig['port']);
            ldap_set_option($conn, $ldapConfig['version'], $ldapConfig['option']);
            $r=ldap_bind($conn, $ldapConfig['adminname'],$ldapConfig['adminpass']);
        }

        $login = false;

        if ($r)
        {
            $base = $ldapConfig['base'];
            $tipoLogin = is_numeric($user) ? "codAluno" : "uid";
            //$search = '(&(objectClass=*)(codAluno='.$user.'))';
            //$search = '(&(objectClass=univates)('.$tipoLogin.'='.$user.'))';
            $search = '(&(objectClass='.$objectClass.')('.$tipoLogin.'='.$user.'))';
            $data = array('dn','cn','displayname','codAluno');
            $idPerson = 'codAluno';
            $sr= ldap_search($conn, $base, $search, $data, (int)$idPerson);
            $info = ldap_get_entries($conn, $sr);
            for ( $i=0; $i < $info['count']; $i++ )
            {
                if ( $info[$i]['dn'] )
                {
                    @$r = @ldap_bind($conn, $info[$i]['dn'], $pass);

                    if( $r )
                    {
                        ldap_close($conn);
                        return $info[0]['codaluno'][0];
                    }
                    else
                    {
                        ldap_close($conn);
                        return 0;
                    }
                }
                else
                {
                    ldap_close($conn);
                    return 0;
                }
            }
        }
        ldap_close($conn);
    }
}
?>
