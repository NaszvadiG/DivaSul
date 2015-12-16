<?php
class Cidades_model extends Default_model
{
    function __construct($table_name='site_cidades')
    {
        if ( strlen($this->table_name) == 0 )
        {
            $this->table_name = $table_name;
        }
        
        parent::__construct();
    }
}