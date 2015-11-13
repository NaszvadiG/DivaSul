<?php
class Template
{
    function header()
    {
        $CI = &get_instance();
        $CI->load->model('Usuarios_model');

        $site_id = $CI->session->userdata('site_id');

        if ( !$CI->input->is_ajax_request() )
        {
            $dados['menu'] = array();
            $dados['site_id'] = $site_id;
            $dados['usuario'] = $CI->Usuarios_model->obter(null, array('id', 'nome'));
            $dados['site_name'] = element('titulo', $CI->db->where('id', $site_id)->get('cms_sites')->row_array());
            $dados['sites'] = current($CI->db->select('COUNT(DISTINCT site_id)', false)->from('cms_permissoes')->where('usuario_id', $CI->session->userdata('usuario_id'))->get()->row_array());
            
            $CI->load->view('template/cabecalho'.$_GET['modo'], $dados);
            if ( $CI->router->class != 'login' )
            {
                // Obtém os admin_modules que o usuário tem permissão de acesso
                $admin_modules = $CI->Usuarios_model->obter_modulos();
                $dados['menu_topo'] = $this->_montar_menu($admin_modules);
            }
            $CI->load->view('template/topo', $dados);
        }
    }

    function _montar_menu($admin_modules = array())
    {
        $menu = array();
        if ( is_array($admin_modules) && count($admin_modules) > 0 )
        {
            foreach ( $admin_modules as $module )
            {
                $menu[] = '<li>'.anchor($module['path'], $module['titulo']).'</li>';
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