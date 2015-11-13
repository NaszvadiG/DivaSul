<?php
class Templates_model extends Default_model
{
    function __construct()
    {
        $this->table_name = 'cms_templates';
        parent::__construct();
    }

    function salvar($dados)
    {
        $dados['site_id'] = $this->session->userdata('site_id');

        return parent::salvar($dados);
    }
}
?>
