<?php
class Banners extends MX_Controller
{
    protected $site_id;

    function index($params=array())
    {
        $this->exibir($params);
    }
    
    function exibir($params=array())
    {
        $this->load->model('Banners_model');

        // Obtém a categoria do banner
        $categoria = $params['categoria_id'];

        // Array de dados para a view
        $dados = array();

        // Obtém os banners
        $this->site_id = $this->data->site['id'];
        $dados['banners'] = $this->Banners_model->listar($categoria, $this->site_id);

        // Obtém as informações da categoria do banner
        $dados['categoria_banner'] = $this->Banners_model->obter_categoria($categoria);

        // Path do banner
        $path = SERVERPATH;
        $path .= 'arquivos/banners/';
        $dados['path'] = $path;

        // Define a view do banner
        $view = 'banner_home';

        // Carrega a view
        $this->load->view($view, $dados);
    }
}
?>
