<?php
class Sites_model extends Default_model
{
    function __construct()
    {
        $this->table_name = 'cms_sites';
        parent::__construct();
    }

    function salvar($content)
    {
        $id = $content['id'];

        if ( empty($id) )
        {
            // inserir template vazio (que será o template default do site)
            $template['titulo'] = $content['titulo'];
            $template['html'] = '';
            $this->db->insert('cms_templates', $template);
            $content['template_id'] = $this->db->insert_id();

            // insere site (com o template padrao)
            $id = parent::salvar($content);

            // Atualiza o site_id do template inserido antes
            $this->db->update('cms_templates', array('site_id'=>$id), array('id'=>$content['template_id']));

            // inserir pagina home (cria a página principal do novo site)
            $page['parent_id'] = 0;
            $page['url'] = '';
            $page['titulo']='Principal';
            $page['type'] = 'html';
            $page['created_by'] = $this->session->userdata('usuario_id');
            $page['site_id'] = $id;
            $this->db->insert('cms_paginas', $page);

            // Executa bash script que cria os diretórios e arquivos default do site novo
            if ( !is_dir(SERVERPATH.$content['dir']) )
            {
                exec('sudo '.APPPATH.'libraries/criar_diretorios_site.sh '.SERVERPATH.' '.APPPATH.' '.$content['dir'], $out);
            }
        }
        else
        {
            $site__old_dir = current(explode('/', element('dir', $this->obter($id))));

            $id = parent::salvar($content);

            // Executa bash script que renomeia o diretório
            if ( !is_dir(SERVERPATH.$content['dir']) && is_dir(SERVERPATH.$site__old_dir) && ($content['dir'] != $site__old_dir) )
            {
                exec('sudo '.APPPATH.'libraries/renomear_diretorios_site.sh '.SERVERPATH.' '.$site__old_dir.' '.$content['dir'], $out);
            }
        }

        return $id;
    }

    // Função que exclui um site (e todas suas dependências)
    function remover($site_id = '')
    {
        $ok = false;
        if ( strlen($site_id) > 0 )
        {
            // permissoes
            $this->db->where('site_id', $site_id)->delete('cms_permissoes');
            // componentes
            $components = $this->db->where('site_id', $site_id)->get('cms_components')->result_array();
            if ( !empty($components) )
            {
                $components_ids = array();
                foreach ( $components as $component )
                {
                    $components_ids[] = $component['id'];
                }
                $this->db->where_in('componente_id', $components_ids)->delete('cms_componentes_paginas');
                $this->db->where('site_id', $site_id)->delete('cms_componentes');
            }

            // paginas
            $this->db->where('site_id', $site_id)->delete('cms_paginas');

            // menus
            $this->db->where('site_id', $site_id)->delete('cms_menus');

            // templetes e paginas
            $templates = $this->db->where('site_id', $site_id)->get('cms_templates')->result_array();
            $this->db->where('site_id', $site_id)->update('cms_templates', array('site_id'=>null));

            // Obtém o diretório do site (univates/dir)
            $site_dir = current(explode('/', element('dir', $this->obter($site_id))));
            if ( is_null($site_dir) )
            {
                $site_dir = array_pop(explode('/', element('path', $this->obter($site_id))));
            }

            $ok = $this->db->where('id', $site_id)->delete('cms_sites');

            // Executa bash script que cira os diretórios e arquivos default do site novo
            if ( $ok && (strlen($site_dir) > 0 ) && is_dir(SERVERPATH.$site_dir) )
            {
                exec('sudo '.APPPATH.'libraries/remover_diretorios_site.sh '.SERVERPATH.' '.$site_dir, $out);
            }

            if ( !empty($templates) )
            {
                foreach ( $templates as $template )
                {
                    $this->db->where('id', $template['id'])->delete('cms_templates');
                }
            }
        }

        return $ok;
    }
}
?>
