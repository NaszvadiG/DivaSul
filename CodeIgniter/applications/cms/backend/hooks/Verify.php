<?php
class Verify
{
    function verify()
    {
        $CI = &get_instance();
        $class = $CI->router->class;

        $current_uri = uri_string();
        $modulo = substr($current_uri, 0, strpos($current_uri, $class)+strlen($class));

        if ( $class != 'login' )
        {
            if ( !$CI->session->userdata('usuario_id') )
            {
                $CI->session->set_flashdata('redirect_url', $current_uri);
                redirect('login');
            }
            else
            {
                $module = $CI->db->where('path = \''.$modulo.'\'')->get('cms_modulos')->row_array();
                if ( !empty($module) )
                {
                    $site_id = $CI->session->userdata('site_id');
                    $usuario_id = $CI->session->userdata('usuario_id');
                    $acesso = $CI->db->where('modulo_id', $module['id'])->where('site_id', $site_id)->where('usuario_id', $usuario_id)->get('cms_permissoes')->row_array();
                    if ( empty($acesso) )
                    {
                        redirect('');     
                    }
                }
            }
        }

    }
}
?>
