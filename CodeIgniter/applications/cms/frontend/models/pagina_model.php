<?php
class Pagina_model extends Default_Model
{
    /**
     * Obtem site de acordo com a url de acesso ($_SERVER['SCRIPT_FILENAME'])
     * sera sempre em relacao a raiz do site (por causa da reescrita de url do apache)
     */
    function obter_site()
    {
        $sql = "SELECT *
                  FROM cms_sites
                 WHERE path = '".SERVERPATH."'";

        $site = $this->db->query($sql)->row_array();

        return $site;
    }

    /**
     * Busca a pagina, comparando a string requisitada ($_SERVER['REQUEST_URI']) com o que tem cadastrado no banco de dados, campo "url" da tabela cms_paginas
     * enquanto não encontra a pagina, remove a ultima parte da url acessada.
     * Exemplo: 'a/b/c' -> 'a/b' -> 'a' -> ''
     * em ultimo caso, vai exibir a pagina inicial
     */
    function obter_pagina($site_id, $url='')
    {
        // Remove barras duplicadas, não há necessidade de ter hotsite/página//página
        $barras_duplas = false;
        while ( strpos($url, '//') )
        {
            $url = str_replace('//', '/', $url);
            $barras_duplas = true;
        }
        if ( $barras_duplas )
        {
            redirect($url);
        }
        // Após verificar se há barras duplicadas, se houver, remove-as e redireciona para a URL "limpa"

        /*
         * Segurança contra while(true)
         * Armazena em um array as URLs já pesquisadas, se repetir encerra o while.
         */
        $urls_utilizadas = array();

        // Obtém as páginas(ativas), filhas de site_id=$site_id e com URL=$url
        // Enquanto não haver páginas, a URL for diferente de '' E a $url ainda não foi pesquisada
        while( (count($pagina=(array)$this->db->select('*')->from('cms_paginas')->where('site_id', $site_id)->where('url', $url)->get()->row_array()) == 0) && ($url != '') && !in_array($url, $urls_utilizadas) )
        {
            // Remove a última parte da URL
            $urls_utilizadas[] = $url;
            $url = preg_replace('/\/?[^\/]+$/', '', $url);
        }

        return $pagina;
    }

    /**
     * Obtem o template, dando prioridade para o template da pagina, se estiver definido
     */
    function obter_template($site_template_id, $pagina_template_id=NULL)
    {
        $template_id = (!empty($pagina_template_id) ? $pagina_template_id : $site_template_id);
        $template = $this->db->select('*')->from('cms_templates')->where('id', $template_id)->get()->row_array();

        return $template;
    }

    /**
     * Obtem os componentes da pagina acessada
     * procurando relacao com o pagina_id,
     * pagina_id 0 significa que o componente vai aparecer em todas as paginas
     * pagina_id negativo siginfica que o componente aparece em todas as paginas exceto nessa de id negativo
     */
    function obter_componentes($site_id, $pagina_id)
    {
        $sql = "SELECT m.*
                  FROM cms_componentes m
                 WHERE m.id IN (SELECT componente_id
                                 FROM cms_componentes_paginas
                                WHERE pagina_id='{$pagina_id}'
                                   OR pagina_id='0'
                                   OR (pagina_id<0 AND componente_id NOT IN (SELECT componente_id
                                                                               FROM cms_componentes_paginas
                                                                              WHERE pagina_id = -{$pagina_id}
                                                                            )
                                      )
                       )
           AND m.site_id = {$site_id}
           AND m.ativo";

        $component = $this->db->query($sql)->result_array();

        $return = array();
        foreach ( $component as $value )
        {
            $return[$value['posicao']][] = $value;
        }

        return $return;
    }

    /**
     * Executa, atraves do Module Extension, o controller do componente chamado,
     * passando junto parametros que sao definidos no admin
     */
    function process_component($component_name, $params, $param_pagina)
    {
        //Trata os parametros passados na chamada do componente (loadposition componente/parametros)
        $parametros_da_pagina = array();
        if ( !empty($param_pagina) && $param_pagina != '' )
        {
            if (preg_match_all('/^([a-z0-9_.-]+)\s*[:=]\s*(.*)/im', $param_pagina, $matches_))
            {
                $parametros_da_pagina = array_combine($matches_[1], $matches_[2]);
            }
        }

        $parametros_do_componente = array();
        if (preg_match_all('/^([a-z0-9_.-]+)\s*[:=]\s*(.*)/im', $params, $matches))
        {
            $parametros_do_componente = array_combine($matches[1], $matches[2]);
        }

        if ( (count($parametros_do_componente) > 0) ||
             (count($parametros_da_pagina) > 0) )
        {
            $params = array_merge($parametros_do_componente, $parametros_da_pagina);
        }

        //Transforma os parametros de string para array associativo (componente)
        /* $component_name:
         *
         * 3 parametros será:
         * frontend/modules/controllers/function => Ex.: nome_módulo/nome_controller/nome_função
         *
         * 2 parâmetros pode ser:
         * frontend/modules/controllers/ => Ex.: nome_módulo/nome_controller
         * ou
         * 2 parâmetros pode ser:
         * frontend/controllers/function => Ex.: nome_controller/nome_função
         *
         * 1 parâmetro
         * frontend/controllers => Ex.: nome_controller
         *
         * Nota: Não criar um (frontend/modules/)modulo/controller que conflite com (frontend/)controller/função.
         */

        // a funcao pode ser nula, e se for nula sera trocada para index(default)
        $component_name = explode('/', $component_name);
        if ( count($component_name) == 2 )
        {
            if ( !file_exists(APPPATH.'controllers/'.$component_name[0].'.php') )
            {
                $method = '/index';
            }
        }

        // verifica se recebeu funcao "module/controller/funcao"
        if (count($component_name)==3)
        {
            $method = '/'.array_pop($component_name);
        }
        $component_name = implode('/', $component_name);

        return Modules::run($component_name.$method, $params);
    }
}
?>
