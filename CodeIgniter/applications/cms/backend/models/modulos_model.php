<?php
class Modulos_model extends Default_model
{
    function __construct()
    {
        $this->table_name = 'cms_modulos';  
        parent::__construct();
    }

    function listar($params=array())
    {
        return parent::listar($params);
    }

    function remover($id)
    {
        $ok = $this->db->delete('cms_permissoes', array('modulo_id'=>$id));
        if ($ok)
        {
            $ok = parent::remover($id);
        }

        return $ok;
    }
}
?>
