<?php
class Noticias extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Noticias_model');
        $this->load->library('MY_Date');
    }

    function exibir()
    {
        $noticia_id = $this->uri->segment(2);

        $dados = array();
        $dados['noticia'] = $this->Noticias_model->obter($noticia_id);

        $this->load->view('noticias_exibir', $dados);    
    }
}
?>
