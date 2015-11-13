<?php
class Menu extends MX_Controller
{
    function index($menu_id='')
    {
        $dados = array();

        // Menu padrão
        if ( is_array($menu_id) )
        {
            $menu_id = $menu_id['menu_id'];
        }
        
        if ( strlen($menu_id) == 0 )
        {
            $menu_id = 1;
        }

        $this->load->model('Menu_model');
        $dados['itens'] = $this->Menu_model->listar_itens($menu_id);

        $this->load->view('menu', $dados);
    }
}
?>
