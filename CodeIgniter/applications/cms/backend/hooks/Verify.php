<?php
class Verify
{
    function verify()
    {
        $CI = &get_instance();
        $class = $CI->router->class;
        $method = $CI->router->method;

        $current_uri = uri_string();
        $modulo = substr($current_uri, 0, strpos($current_uri, $class)+strlen($class));

        if ( $class != 'login' )
        {
            if ( !$CI->session->userdata('usuario_id') )
            {
                $CI->session->set_flashdata('redirect_url', $current_uri);
                redirect('login');
            }
            elseif ( $class == 'usuarios' && $method == 'editar' )
            {
                // Mesmo que não tenha acesso ao módulo de usuários, permite visualizar seu próprio cadastro.
                if ( $CI->uri->segment(3) != $CI->session->userdata('usuario_id') )
                {
                    redirect();
                }
            }
            else
            {
                $module = $CI->db->where('path = \''.$modulo.'\'')->get('cms_modulos')->row_array();
                if ( !empty($module) )
                {
                    // Add na sessão o módulo atual
                    $CI->session->set_userdata(array('modulo_id'=>$module['id']));

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
