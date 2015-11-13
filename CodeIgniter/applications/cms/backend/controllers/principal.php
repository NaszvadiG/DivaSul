<?php
class Principal extends CI_Controller
{
    function index()
    {
        $site_id = $this->session->userdata('site_id');
        $dados['site_name'] = element('titulo', $this->db->get_where('cms_sites', array('id'=>$site_id))->row_array());
        $this->load->view('principal', $dados);
    }
}
?>
