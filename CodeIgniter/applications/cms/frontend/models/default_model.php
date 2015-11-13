<?php
class Default_model extends CI_Model
{
    protected $table_name;
    private $site_id;
    private $site_dir;

    function __construct()
    {
        parent::__construct();
        $this->site_id = $this->data->site['id'];
        $this->site_dir = $this->data->site['dir'];
    }

    // Define a tabela principal desse Model
    function set_table_name($table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * Lista os registros
     * @params (array) $params Pode receber como parâmetro um array associativo:
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

        $sql = " SELECT " .
                       implode(', ', (array)$params['columns'] ) .
               " FROM ".$this->table_name;

        if ( is_array($params['where']) && (count($params['where']) > 0) )
        {
            $sql .= ' WHERE '. implode(' AND ', $params['where']);
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
     * Conta os registros
     * @params (array) $params Pode receber como parâmetro um array associativo:
     * $params['where'] = Array que pode ser formado por uma restrição
     *
     * $params['limit'] = Limit de registros
     * Ex.: 20
     *
     * $params['offset'] = Retornar registros a partir do registro Nº tal
     * Ex.: 0
     */
    function count($params=array())
    {

        $sql = " SELECT COUNT(id) FROM ".$this->table_name;

        if ( is_array($params['where']) && (count($params['where']) > 0) )
        {
            $sql .= ' WHERE '. implode(' AND ', $params['where']);
        }

        if ( $params['limit'] )
        {
            $sql .= " LIMIT {$params['limit']} ";
        }

        if ( $params['offset'] )
        {
            $sql .= " OFFSET {$params['offset']} ";
        }
        $registros = $this->db->query($sql)->row_array();

        return current($registros);;
    }

    /**
     * Obtém um registro
     * @param (int) $id Código do registro
     */
    function obter($id)
    {
        $registro = $this->db->select('*')->from($this->table_name)->where('id', $id)->get()->row_array();

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
}
?>
