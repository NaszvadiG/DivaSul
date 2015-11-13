<?php
class Logs_model extends Default_model
{
    function __construct()
    {
        // Nome da tabela
        $this->table_name = 'cms_logs';

        parent::__construct();
    }

    function obter_dados_anteriores($tabela, $coluna, $id_registro)
    {
        $dados_anteriores = $this->db->select($coluna)->from($tabela)->where('id', $id_registro)->get()->row_array();

        return $dados_anteriores;
    }
}
?>
