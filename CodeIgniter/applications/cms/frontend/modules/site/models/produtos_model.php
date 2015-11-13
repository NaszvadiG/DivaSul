<?php
class Produtos_model extends Default_model
{
    function __construct()
    {
        $this->table_name = 'site_produtos';
        parent::__construct();
    }

    function get_sub_categorias($categoria_id)
    {
        $categs = array();
        $categorias = $this->db->select('id')->from($this->table_name.'_categorias')->where('ativo', '1')->where('parent_id', $categoria_id)->get()->result_array();
        foreach ( $categorias as $categoria )
        {
            $categs[] = $categoria;
            foreach ( $this->get_sub_categorias($categoria['id']) as $cat )
            {
                $categs[] = $cat;
            }
        }

        return $categs;
    }

    function get_produto_by_link($link='')
    {
        $produto = $this->db->select('*')->from($this->table_name)->where('ativo', '1')->where('link', $link)->get()->row_array();

        return $produto;
    }

    function get_categoria_by_link($link='')
    {
        $categoria = $this->db->select('*')->from($this->table_name.'_categorias')->where('ativo', '1')->where('link', $link)->get()->row_array();
        return $categoria;
    }

    function listar_categorias($parent_id = 0)
    {
        $categorias = $this->db->select('*')->from($this->table_name.'_categorias')->where('ativo', '1')->where('parent_id', $parent_id)->order_by('parent_id ASC, ordem ASC, titulo ASC')->get()->result_array();

        return $categorias;
    }

    function obter_categoria($categoria_id=0)
    {
        $categoria = $this->db->select('*')->from($this->table_name.'_categorias')->where('ativo', '1')->where('id', $categoria_id)->get()->row_array();

        return $categoria;
    }

    function salvar_orcamento($orcamento)
    {
        $ok = $this->db->insert('site_orcamentos', $orcamento);

        return $ok;
    }
}
?>
