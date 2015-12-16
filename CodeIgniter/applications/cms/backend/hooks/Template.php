<?php
class Template
{
    function header()
    {
        $CI = &get_instance();
        $CI->load->model('Sites_model');
        $CI->load->model('Usuarios_model');
        $CI->load->model('Modulos_model');

        $site_id = $CI->session->userdata('site_id');

        if ( !$CI->input->is_ajax_request() )
        {
            $dados['menu'] = array();
            $dados['usuario'] = $CI->Usuarios_model->obter(null, array('id', 'nome'));
            $dados['site'] = $CI->Sites_model->obter($site_id);
            $dados['sites'] = current($CI->db->select('COUNT(DISTINCT site_id)', false)->from('cms_permissoes')->where('usuario_id', $CI->session->userdata('usuario_id'))->get()->row_array());
            
            // Obtém os admin_modules que o usuário tem permissão de acesso
            $modulos = $CI->Usuarios_model->obter_modulos();
            $modulo_id = $CI->session->userdata('modulo_id');
            $dados['menu_topo'] = $this->_montar_menu($modulos, $modulo_id);
            $dados['modulo'] = $CI->Modulos_model->obter($modulo_id);

            $CI->load->view('template/cabecalho', $dados);
            if ( $CI->router->class != 'login' )
            {
                $CI->load->view('template/topo', $dados);
            }
        }
    }

    function _montar_menu($modulos = array(), $modulo_ativo=NULL)
    {
        $menu = array();
        if ( is_array($modulos) && count($modulos) > 0 )
        {
            foreach ( $modulos as $modulo )
            {
                $active = '';
                if ( $modulo['id'] == $modulo_ativo )
                {
                    $active = ' active';
                }
                $menu[] = '<li class="treeview'.$active.'">'.anchor($modulo['path'], $modulo['titulo']).'</li>';
            }
        }

        return implode('', $menu);
    }

    function footer()
    {
        $CI = & get_instance();
        if ( !$CI->input->is_ajax_request() )
        {
            $CI->load->view('template/assinatura');
            $CI->load->view('template/rodape');
        }
    }
}
?>
