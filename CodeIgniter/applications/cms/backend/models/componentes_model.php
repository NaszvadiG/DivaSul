<?php
class Componentes_model extends Default_model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'cms_componentes';
        $this->site_id = $this->session->userdata('site_id');
    }

    function listar($where=array(), $site_id=null)
    {
        $site_id = ( (!is_null($site_id)) ? $site_id : $this->site_id);
        $this->db->select('m.id, m.path, m.titulo, m.posicao, m.ordem, m.ativo, sum(mp.pagina_id) as soma');
        $this->db->where('m.site_id',$site_id);
        $this->db->where($where);
        $this->db->order_by('m.posicao, m.ordem, m.id');
        $this->db->group_by('m.id, m.path, m.titulo, m.posicao, m.ordem, m.ativo');
        $this->db->join('cms_componentes_paginas mp','mp.componente_id = m.id','left');
        $this->db->from('cms_componentes m');

        return $this->db->get();
    }

    function salvar($componente)
    {
        if ( empty($id) )
        {
            $componente['criado_por'] = $this->session->userdata('usuario_id');
            $componente['site_id'] = $this->site_id;
        }
        else
        {
            $componente['content_last'] = current($this->db->select('contente')->from($this->table_name)->where('id', $componente['id'])->get()->row_array());
            $componente['path_last'] = current($this->db->select('path')->from($this->table_name)->where('id', $componente['id'])->get()->row_array());
        }

        // Salva
        $id = parent::salvar($componente);

        return $id;
    }

    function delete($id)
    {
        $ok = $this->remover_paginas($id);

        if ( $ok )
        {
            $this->db->where('id', $id);
            $this->db->where('site_id', $this->site_id);
            $ok = $this->db->delete($this->table_name);
        }

        return $ok;
    }

    // Remove os registros deste componente em cms_componentes_paginas
    function remover_paginas($componente_id)
    {
        $this->db->where('componente_id', $componente_id);
        return $this->db->delete('cms_componentes_paginas');
    }

    function listar_paginas()
    {
        $this->db->select('id, url, titulo');
        $this->db->where('site_id',$this->site_id);
        $this->db->order_by('url, ordem');
        $paginas = $this->db->get('cms_paginas')->result_array();
        $list = array();
        foreach ( $paginas as $p )
        {
            if ( $p['url'] == '' )
            {
                $p['url'] = $p['titulo'];
            }
            $list[$p['id']] = $p['url'];
        }

        return $list;
    }

    function salvar_posicao($componente_id, $componente_paginas)
    {
        $this->remover_paginas($componente_id);
        switch ( $componente_paginas['tipo'] )
        {
            case 'todas':
                $this->db->insert('cms_componentes_paginas',array('componente_id'=>$componente_id,'pagina_id'=>'0'));
            break;
            case 'somente_selecionadas':
            case 'exceto_selecionadas':
                if ( !empty($componente_paginas['ids']) )
                {
                    foreach ( $componente_paginas['ids'] as $pagina_id )
                    {
                        $this->db->insert('cms_componentes_paginas',array('componente_id'=>$componente_id,'pagina_id'=>$pagina_id));
                    }
                }
            break;
            default: break;//nenhuma
        }
    }

    function obter_paginas($componente_id)
    {
        $componente_paginas = array('tipo'=>'all','ids'=>array());
        if ( !empty($componente_id) )
        {
            $this->db->select('pagina_id');
            $this->db->where('componente_id',$componente_id);
            $paginas = $this->db->get('cms_componentes_paginas')->result_array();
            if ( !empty($paginas) )
            {
                foreach ( $paginas as $p )
                {
                    $componente_paginas['ids'][] = $p['pagina_id'];
                }
            }
        }

        if ( empty($componente_paginas['ids']) )
        {
            $componente_paginas['tipo'] = 'todas';
        }
        elseif ( count($componente_paginas['ids'])==1 && $componente_paginas['ids'][0]=='0' )
        { 
            $componente_paginas['tipo'] = 'nenhuma';
        }
        elseif ( count($componente_paginas['ids'])>=1 && $componente_paginas['ids'][0]>0 )
        {
            $componente_paginas['tipo'] = 'somente_selecionadas';
        }
        elseif ( count($componente_paginas['ids'])>=1 && $componente_paginas['ids'][0]<0 )
        {
            $componente_paginas['tipo'] = 'exceto_selecionadas';
        }

        return $componente_paginas;
    }
}
?>