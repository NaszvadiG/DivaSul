<?php
class Default_model extends CI_Model
{
    // Tabela principal desse Model
    protected $table_name;

    // Variável de controle para gravar logs
    protected $gravar_log = false;

    protected $site_id;
    protected $usuario_id;
    protected $site_dir;

    function __construct()
    {
        parent::__construct();
        $this->site_id = $this->session->userdata('site_id');
        $this->usuario_id = $this->session->userdata('usuario_id');
        $this->site_dir = $this->session->userdata('site_dir');
    }

    // Define a tabela principal desse Model
    function set_table_name($table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * Pode receber como parâmetro um array associativo:
     * $params['columns'] = Array com as colunas da tabela
     * $params['where'] = Array que pode ser formado por uma restrição 
     * $params['order_by'] = Ordenação
     * Ex.: 'data, titulo'
     *
     * $params['limit'] = Limit de registros
     * Ex.: 20
     *
     * $params['offset'] = Retornar registros a partir do registro Nº tal
     * Ex.: 0
     */
    function listar($params=array())
    {
        if ( count((array)$params['columns']) == 0 )
        {
            $params['columns'] = '*';
        }
        else
        {
            foreach ( $params['columns'] as $k=>$coluna )
            {
                if ( strpos($coluna, '||') )
                {
                    list(
                        $coluna,
                        $as
                    ) = explode('AS', $coluna);
                    $params['columns'][$k] = 'CONCAT('.str_replace('||', ',', $coluna).')';
                    if ( strlen($as) > 0 )
                    {
                        $params['columns'][$k] .= 'AS '.$as;
                    }
                }
            }
        }

        $sql = " SELECT " .
                       implode(', ', (array)$params['columns'] ) .
               " FROM ".$this->table_name;

        if ( is_array($params['where']) && (count($params['where']) > 0) )
        {
            $sql .= ' WHERE '. implode( ' AND ', $params['where'] );
        }
        if ( $params['order_by'] )
        {
            $sql .= " ORDER BY {$params['order_by']} ";
        }

        if ( $params['limit'] )
        {
            $sql .= " LIMIT {$params['limit']} ";
        }

        if ( $params['offset'] )
        {
            $sql .= " OFFSET {$params['offset']} ";
        }

		$registros = $this->db->query($sql)->result_array();

        return $registros;
    }

    /**
     * Conta quantos registros o listar trará
     * @param $where = array contendo as restrições para a consulta
     */ 
    function obter_total($where)
    {
        $sql = "SELECT COUNT(*) AS total
                  FROM ".$this->table_name;

        if ( is_array($where) && (count($where) > 0) )
        {
            $sql .= ' WHERE '. implode( ' AND ', $where );
        }

        $total = current($this->db->query($sql)->row_array());

        return $total;
    }

    /**
     * Obtém um registro
     * @param (int) $id Código do registro
     * @param (array) $columns Array com as colunas da tabela
     */
    function obter($id, $columns=array())
    {
        if ( count($columns) == 0 )
        {
            $columns = array('*');
        }

        $registro = $this->db->select(implode(', ', $columns))->from($this->table_name)->where('id', $id)->get()->row_array();

        return $registro;
    }

    /**
     * Salva na base de dados um registro
     * $param (array) $dados Array com as informações do registro a ser inserido/atualizado
     */
    function salvar($dados)
    {
        $insert = true;

        // Se existe, atualiza o registro
        if ( strlen($dados['id']) > 0 )
        {
            $registro = $this->obter($dados['id']);
            if ( count($registro) > 0 )
            {
                $insert = false;
            }
        }
        else
        {
            unset($dados['id']);
        }

        if ( $insert )
        {
            $ok = $this->db->insert($this->table_name, $dados);
            if ( $ok && !$dados['id'] )
            {
                $id = $this->db->insert_id();
            }
            else
            {
                $id = $dados['id'];
            }
        }
        else
        {
            $id = $dados['id'];

            // Grava um log da alteração
            if ( $this->gravar_log )
            {
                $log_ok = $this->gravar_log($this->table_name, $id, $dados);
            }

            // Evita que faça update sem especificar ID
            if ( $id )
            {
                $this->db->where('id', $id);
                unset($dados['id']);

                $ok = $this->db->update($this->table_name, $dados);
            }
        }

        if ( !$ok )
        {
            $id = NULL;
        }

        return $id;
    }

    /**
     * Remove um registro da base de dados
     * @param (int) $id Código do registro a ser removido
     */
    function remover($id)
    {
        $this->db->where('id', $id);
        $ok = $this->db->delete($this->table_name);

        return $ok;
    }

    /**
     * Função que verifica se dados alterados na tabela e grava log
     * $tabela = tabela de onde vem as alteracoes
     * $id_registro = id registro modificado
     * $dados = dados do registro 
     */
    function gravar_log($table, $id_registro, $dados_novos = array())
    {
        $usuario_atual = $this->session->userdata('usuario_id');
        $dados_old = $this->db->select('*')->from($table)->where('id', $id_registro)->get()->row_array();
        
        foreach ( array_keys($dados_novos) as $coluna )
        {
            if ( $dados_novos[$coluna] != $dados_old[$coluna] )
            {
                $log = array(
                    'tabela' => $table,
                    'id_registro' => $id_registro,
                    'campo' => $coluna,
                    'valor' => $dados_old[$coluna],
                    'usuario_id' => $usuario_atual,
                    'data_hora' => date('Y-m-d H:i:s')
                );
                $ok = $this->db->insert('cms_logs', $log);
            }
        }
    }

    /**
     * Obtém o próximo id
     */
    function proximo_id()
    {
        //$sql = "SELECT nextval('{$this->table_name}_id_seq'::regclass)";
        $sql = "SELECT MAX(id) FROM ".$this->table_name;
        $next_id = current($this->db->query($sql)->row_array());

        return $next_id + 1;
    }

    /**
     * Lista os registros filhos
     * @param integer $parent_id
     * @param array $colunas
     */
    function obter_filhos($parent_id, $colunas)
    {
        $filhos = $this->db->select(implode(', ', $colunas))->from($this->table_name)->where('parent_id', $parent_id)->get()->result_array();

        return $filhos;
    }
}
?>
