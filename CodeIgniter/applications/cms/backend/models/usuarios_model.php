<?php
class Usuarios_model extends Default_model
{
    function __construct()
    {
        // Nome da tabela
        $this->table_name = 'cms_usuarios';
        parent::__construct();
    }

    function obter($id='', $columns=array())
    {
        if ( strlen($id) == 0 )
        {
            $id = $this->session->userdata('usuario_id');
        }
    
        return parent::obter($id, $columns);
    }

    function autenticar($usuario, $senha)
    {
        $id = current($this->db->select('id')->from($this->table_name)->where('LOWER(usuario)', strtolower($usuario))->where('senha', $senha)->get()->row_array());
    
        return $id;
    }

    function obter_permissoes($usuario_id)
    {
        $permissoes = $this->db->select('site_id, modulo_id')->from('cms_permissoes')->where('usuario_id', $usuario_id)->get()->result_array();

        return $permissoes;
    }

    function adicionar_permissao($usuario_id, $site_id, $modulo_id)
    {
        $ok = $this->db->insert('cms_permissoes', array('usuario_id'=>$usuario_id, 'site_id'=>$site_id, 'modulo_id'=>$modulo_id));

        return $ok;
    }

    function remover_todas_permissoes($id)
    {
        $ok = $this->db->delete('cms_permissoes', array('usuario_id' => $id));

        return $ok;
    }

    function deletar($id)
    {
        $ok = $this->remover_todas_permissoes($id);
        $ok = $this->db->where('id', $id)->delete($this->table_name);
    }

    function obter_modulos($usuario_id=null, $site_id=null)
    {
        if ( is_null($usuario_id) )
        {
            $usuario_id = $this->session->userdata('usuario_id');
        }

        if ( is_null($site_id) )
        {
            $site_id = $this->session->userdata('site_id');
        }

        $modulos = $this->db->select('m.*')->from('cms_modulos m')->from('cms_permissoes p')->where('p.modulo_id = m.id')->where('p.usuario_id', $usuario_id)->where('p.site_id', $site_id)->order_by('m.titulo')->get()->result_array();

        return $modulos;
    }

    function remover_permissao($usuario_id, $modulo_id)
    {
        $ok = $this->db->delete('cms_permissoes', array('usuario_id' => $usuario_id, 'modulo_id' => $modulo_id));

        return $ok;
    }
}
?>
