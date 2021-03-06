<?php
class Agenda_model extends Default_model
{
    function __construct($table_name='site_agenda')
    {
        if ( strlen($this->table_name) == 0 )
        {
            $this->table_name = $table_name;
        }
        
        parent::__construct();
    }

    function salvar($dados)
    {
        if ( $table_name == 'site_agenda' )
        {
            $dados['criado'] = date('Y-m-d H:i:s');
            $dados['criado_por'] = $this->usuario_id;
        }

        return parent::salvar($dados);
    }

    function proxima_ordem()
    {
        $ordem = current($this->db->select('MAX(ordem)+10')->from($this->table_name)->get()->row_array());

        return $ordem;
    }

    function listar_categorias()
    {
        $categorias = $this->db->select('*')->from('site_agenda_categorias')->where('ativo', 1)->get()->result_array();

        return $categorias;
    }
}