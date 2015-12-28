<?php
class Clientes_model extends Default_model
{
    function __construct()
    {
        // Nome da tabela
        $this->table_name = 'site_clientes';
        parent::__construct();
    }
}
?>
