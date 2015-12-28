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

    function kit_obter_componentes($produto_id)
    {
        $produtos = array();
        $componentes = $this->db->select('componente_id')->from('site_produtos_kits')->where('produto_id', $produto_id)->get()->result_array();
        if ( is_array($componentes) && count($componentes) > 0 )
        {
            foreach ( $componentes as $componente )
            {
                $produtos[] = $this->obter($componente['componente_id']);
            }
        }

        return $produtos;
    }

    function buscar($busca='')
    {
        $produtos = $this->db->select('*')->from($this->table_name)->like('LOWER(titulo)', strtolower($busca))->get()->result_array();

        return $produtos;
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

    function buscar_marca($busca='')
    {
        $marcas = $this->db->select('marca')->distinct('marca')->from($this->table_name)->like('LOWER(marca)', strtolower($busca))->get()->result_array();

        return $marcas;
    }

    function alterar_estoque($produto_id, $quantidade, $obs)
    {
        // Obtem o produto
        $produto = $this->obter($produto_id);
        if ( $produto['tipo_id'] == '2' )
        {
            $produtos = $this->kit_obter_componentes($produto_id);
            foreach ( $produtos as $produto )
            {
                $ok = $this->altera_estoque($produto['id'], $quantidade, $obs);
                if ( !$ok )
                {
                    break;
                }
            }
        }
        else
        {
            $ok = $this->altera_estoque($produto['id'], $quantidade, $obs);
        }

        return $ok;
    }

    private function altera_estoque($produto_id, $quantidade, $obs)
    {
        // Obtem o produto
        $produto = $this->obter($produto_id);

        // Add movimentação de estoque
        $dados = array(
            'produto_id' => $produto_id,
            'quantidade' => $quantidade,
            'obs' => $obs
        );
        $this->db->insert('site_produtos_estoque', $dados);

        // Obtém o estoque atualizado
        $estoque_anterior = $this->obter_estoque($produto_id);
        // Atualiza o estoque atual
        $produto['estoque'] = $estoque_anterior;
        $ok = $this->salvar($produto);

        return $ok;
    }

    function obter_estoque($produto_id)
    {
        $estoque_atual = 0;

        // Obtém o produto
        $produto = $this->obter($produto_id);

        // Se kit, pega o menor estoque
        if ( $produto['tipo_id'] == '2' )
        {
            $estoques = $this->db->select('KIT.componente_id, SUM(EST.quantidade) AS estoque_atual')->from('site_produtos_estoque EST')->join('site_produtos_kits KIT', 'KIT.componente_id = EST.produto_id', 'INNER')->where('KIT.produto_id', $produto_id)->group_by('KIT.componente_id')->get()->result_array();
            foreach ( $estoques as $k => $estoque )
            {
                if ( ($k == 0) || ($estoque_atual > $estoque['estoque_atual']) )
                {
                    $estoque_atual = $estoque['estoque_atual'];
                }
            }
        }
        else
        {
            $estoque = $this->db->select('SUM(quantidade) AS estoque_atual')->from('site_produtos_estoque')->where('produto_id', $produto_id)->get()->row_array();
            $estoque_atual = $estoque['estoque_atual'];
        }

        return $estoque_atual;
    }
}
