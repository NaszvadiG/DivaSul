<?php
class Funcionarios_model extends Default_model
{
    function __construct()
    {
        // Nome da tabela
        $this->table_name = 'site_funcionarios';
        parent::__construct();
    }

    function obter_por_usuario_id($usuario_id)
    {
        $funcionario = $this->db->select('*')->from($this->table_name)->where('usuario_id', $usuario_id)->get()->row_array();

        return $funcionario;
    }
}
?>
