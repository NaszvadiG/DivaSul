<?php
class Galeria extends MX_Controller
{
    private $path;

    function __construct()
    {
        parent::__construct();

        $this->site_id = $this->session->userdata('site_id');
        $this->path = FCPATH.'/arquivos/';
        $this->url = base_url('arquivos').'/';
    }

    function index($params)
    {
        $dados = array();
        $dados['path'] = $this->path;

        if ( is_array($params) && strlen($params['path']) > 0 )
        {
            $dados['path'] .= $params['path'];
            $this->url .= $params['path'].'/';
        }
        elseif ( strlen($params) > 0 )
        {
            $dados['path'] .= $params;
            $this->url .= $params.'/';
        }
        $dados['url'] = $this->url;

        if ( $dados['path'] == $this->path )
        {
            $dados['erro'] = 'Parâmetro <b>path</b> é necessário!';
        }
        else
        {
            $fotos = glob($dados['path']."/thumb_*.*", GLOB_BRACE);
            if ( $fotos )
            {
                $dados['fotos'] = $fotos;
            }
        }

        $this->load->view('galeria', $dados);
    }
}
?>
