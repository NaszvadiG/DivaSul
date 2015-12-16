<?php
class Produtos_model extends Default_model
{
    function __construct($table_name='site_produtos')
    {
        if ( strlen($this->table_name) == 0 )
        {
            $this->table_name = $table_name;
        }
        
        parent::__construct();
    }

    function salvar($dados)
    {
        if ( $table_name == 'site_produtos' )
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
        $categorias = $this->db->select('*')->from('site_produtos_categorias')->where('ativo', 1)->get()->result_array();

        return $categorias;
    }

    function buscar($busca='')
    {
        $marcas = $this->db->select('marca')->distinct('marca')->from($this->table_name)->like('LOWER(marca)', strtolower($busca))->get()->result_array();
        return $marcas;
    }

    function alterar_estoque($produto_id, $quantidade, $obs)
    {
        $dados = array(
            'produto_id' => $produto_id,
            'quantidade' => $quantidade,
            'obs' => $obs
        );

        $this->db->insert('site_produtos_estoque', $dados);
        $produto = $this->obter($produto_id);
        $produto['estoque'] += $quantidade;
        $this->salvar($produto);
    }

    function obter_estoque($produto_id)
    {
        $estoque = $this->db->select('SUM(quantidade) AS estoque_atual')->from('site_produtos_estoque')->where('produto_id', $produto_id)->get()->row_array();

        return $estoque['estoque_atual'];
    }
}
