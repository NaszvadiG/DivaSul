<?php
class Breadcrumbs extends MX_Controller
{
    function index($params = array())
    {
        $item = $this->data->page;
        $caminho = array();
        while ( $item['id'] > 0 )
        {
            $caminho[] = $item;
            $item = $this->db->where('id',$item['parent_id'])->get('cms_pages')->row_array();
        }
        $caminho = array_reverse($caminho);
?>
<div class="breadcrumbs">
<?php
        $count = count($caminho);
        $separator = ($params['separator'])? $params['separator'] : '>';

        for ( $i=0; $i < $count; $i++ )
        {
            // If not the last item in the breadcrumbs add the separator
            if ( $i < $count-1 )
            {
                echo '<a href="'.base_url($caminho[$i]['url']).'" class="pathway">'.$caminho[$i]['title'].'</a>';
            }
            else
            {
                echo '<span>'.$caminho[$i]['title'].'</span>';
            }
            if ( $i < $count-1 )
            {
                echo ' '.$separator.' ';
            }
        }

        $page_id = $this->data->page['id'];
        $usuario_id = $this->session->userdata('usuario_id');
        $site_backend_id = $this->session->userdata('site_id');
        $site_frontend_id = $this->db->query("select id from cms_sites where path='".FCPATH."'")->row_array();

        //verifica se tem sessão de usuário no no mesmo site acessado
        if ( !empty($usuario_id) && ($site_backend_id == $site_frontend_id['id']) )
        {
            $module_paginas = $this->db->get_where('cms_admin_modules',array('path'=>'paginas'))->row_array();
            $permissao = $this->db->get_where('cms_admin_permissions',array('site_id'=>$site_frontend_id['id'],'usuario_id'=>$usuario_id,'admin_module_id'=>$module_paginas['id']))->row_array();

            if ( $permissao )
            {
                echo ' <a href="'.base_url('../cms/paginas/editar/'.$page_id).'" title="(Editar página)" target="_blank"><img src="'.base_url('../cms/css/icons/edit.png').'" alt="(Editar página)" /></a>';
            }
        }
?>
</div>
<?php
    }
}
?>
