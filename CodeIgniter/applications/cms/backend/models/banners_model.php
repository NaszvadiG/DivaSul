<?php
class Banners_model extends Default_model
{
    function __construct()
    {
        // Caso queira que seja armazenado LOG
        $this->gravar_log = true;
        parent::__construct();
    }

    function listar_categorias($site_id=null)
    {
        $query = $this->db->select('*')->from('cms_banners_categorias');

        if ( !is_null($site_id) )
        {
            $query->where(
                array(
                    'site_id' => $site_id
                )
            );
        }

        $categorias = $query->get()->result_array();

        return $categorias;
    }

    function obter_categoria($categoria_id)
    {
        $categoria = $this->db->select('*')->from('cms_banners_categorias')->where(array('id'=>$categoria_id))->get()->row_array();

        return $categoria;
    }

    function max_ordem($categoria_id)
    {
        $max_ordem = current($this->db->select('MAX(ordem) as ordem')->from($this->table_name)->where(array('categoria_id'=>$categoria_id))->get()->row_array());

        return $max_ordem;
    }
}
?>
