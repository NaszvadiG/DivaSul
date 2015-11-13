<?php
class Paginas_model extends Default_model
{
    function __construct()
    {
        // Nome da tabela
        $this->table_name = 'cms_paginas';
        
        // Caso queira que seja armazenado LOG
        $this->gravar_log = true;
        parent::__construct();
    }

    function salvar($pagina)
    {
        // Se não tem ID (inserção)
        if ( strlen($pagina['id']) == 0 )
        {
            $pagina['criado'] = date('Y-m-d H:i:s');
            $pagina['criado_por'] = $this->usuario_id;
            $pagina['site_id'] = $this->site_id;
        }
        // Se tem ID (edição)
        else
        {
            // Se já existe, mantém o created
            if ( strlen($pagina['id']) > 0 )
            {
                $old = $this->db->select('criado, site_id, content')->from($this->table_name)->where('id', $pagina['id'])->get()->row_array();
                $pagina['criado'] = $old['criado'];
                $pagina['site_id'] = $old['site_id'];
                $pagina['criado_por'] = $old['criado_por'];
                // Conteúdo anterior
                if ( $pagina['content'] != $old['content'] )
                {
                    $pagina['content_last'] = $old['content'];
                }
            }

            // Atualiza a URL das páginas filho
            if ( $pagina['parent_id'] > 0 )
            {
                $this->atualizar_url_filhos($pagina['id'], $pagina['url']);
            }
        }

        // Salva
        $id = parent::salvar($pagina);

        return $id;
    }

    /**
     * Executado apos atualizar uma página
     */
    function atualizar_url_filhos($parent_id, $parent_url)
    {
        $filhos = $this->db->select('id, titulo, url')->from($this->table_name)->where('parent_id', $parent_id)->get()->result_array();
        if ( !is_array($filhos) || (count($filhos) == 0) )
        {
            foreach ( $filhos as $pagina )
            {
                if (strpos($pagina['url'], $parent_url)!==0)
                {
                    $alias = generate_url($pagina['titulo']);
                    $separator = ($parent_url != '' ? '/' : '');
                    $new_url = $parent_url.$separator.$alias;
                    $this->db->update($this->table_name, array('url'=>$new_url), array('id'=>$pagina['id']));
                    $this->atualizar_url_filhos($pagina['id'], $new_url);
                }
            }
        }
    }

    function obter_menus()
    {
        $menus = $this->db->select('id, titulo')->from('cms_menus')->where('site_id', $this->site_id)->order_by('titulo')->get()->result_array();

        /*
        $array_menus = array_merge(
            array(0 => '-- Selecione --'),
            montar_assoc($menus)
        );
         */
        $array_menus = array(
            NULL => '-- Selecione --'
        );
        foreach ( $menus as $menu )
        {
            $array_menus[$menu['id']] = $menu['titulo'];
        }

        return $array_menus;
    }

    function obter_templates()
    {
        // Obtém o código do template padrão
        $id_template_padrao = current($this->db->select('template_id')->from('cms_sites')->where('id', $this->session->userdata('site_id'))->get()->row_array());
        // Obtém os dados do template padrão
        $template_padrao = $this->db->select('id, titulo')->from('cms_templates')->where('id', $id_template_padrao)->get()->row_array();

        // Obtém os templates do site (ou sem site_ID)
        $templates = $this->db->select('id, titulo')->from('cms_templates')->where('site_id', $this->session->userdata('site_id'))->or_where('site_id', null)->order_by('titulo')->get()->result_array();

        //$array_templates = montar_assoc($templates);
        $array_templates = array(
            $template_padrao['id'] => $template_padrao['titulo']
        );
        foreach ( $templates as $template )
        {
            $array_templates[$template['id']] = $template['titulo'];
        }

        return $array_templates;
    }

    function listar_tipos()
    {
        return array(
            'html' => 'Conteúdo estático', 
            'link' => 'Link', 
            'componente' => 'Componente', 
            'separador' => 'Separador'
        );
    }
}
?>
