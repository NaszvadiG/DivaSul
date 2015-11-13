<?php
class Banners_model extends Default_model
{
    function __construct()
    {
        $this->table_name = 'cms_banners';
        parent::__construct();
    }

    function listar($categoria_id, $site_id, $limit=0)
    {
        $query = $this->db->select('*');
        $query->from($this->table_name);
        $query->where('categoria_id', $categoria_id);
        $query->where('(dt_inicio IS NULL OR dt_inicio <= NOW())', null, false);
        $query->where('(dt_fim IS NULL OR dt_fim >= NOW())', null, false);
        $query->where('ativo', '1');
        $query->order_by('ordem');
        if ( (int)$limit > 0 )
        {
            $query->limit($limit);
        }

        $banners = $query->get()->result_array();

        return $banners;
    }

    function obter_categoria($categoria_id)
    {
        $categoria = $this->db->select('*')->from('cms_banners_categorias')->where(array('id'=>$categoria_id))->get()->row_array();

        return $categoria;
    }
}
?>