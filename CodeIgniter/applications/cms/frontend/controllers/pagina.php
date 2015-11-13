<?php
class Pagina extends MX_Controller
{
    private $CI;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Pagina_model');

        // Instância do CodeIgniter
        $this->CI = &get_instance();

        if ( !$this->CI->data )
        {
            $this->CI->data = new stdClass();
        }
        if ( !$this->CI->data->site )
        {
            $this->CI->data->site = array();
        }
    }

    function index($uri='')
    {
        /**
         * Obtem o site que esta sendo acessado (tabela cms_sites)
         * essa verificação se dá através do caminho da pasta atual (relativo ao index.php)
         */
        $site = $this->Pagina_model->obter_site();

        if ( !is_array($site) || count($site) == 0 )
        {
            echo 'Site não encontrado.';
            return;
        }
        else
        {
            $http = 'http';
            $port = '';
            // Se tem porta
            if ( strlen($_SERVER['SERVER_PORT']) > 0 )
            {
                // Se for diferente da padrão (80)
                if ( $_SERVER['SERVER_PORT'] != 80 )
                {
                    $port = ':'.$_SERVER['SERVER_PORT'];
                }

                // Se for https
                if ( $_SERVER['SERVER_PORT'] == 443 )
                {
                    $http .= 's';
                }
            }

            $site['url'] = $http.'://'.$_SERVER['SERVER_NAME'].$port.'/'.$site['dir'].'/';
        }
        $site['url'] = '';
        $this->CI->data->site = $site; //define o "$this->site"

        // Obtém a página
        $pagina = $this->Pagina_model->obter_pagina($site['id'], uri_string());
        if ( empty($pagina) )
        {
            echo 'Site sem página inicial.';
            return;
        }
        // define a página, o "$this->pagina"
        $this->CI->data->pagina = $pagina;
        // Monta o HTML da página
        $pagina['content'] = $this->_carregar_conteudo($pagina);

        // Se for do tipo link (redirect)
        if ( $pagina['tipo'] == 'link' )
        {
            $params = unserialize($pagina['content']);
            redirect($params['link']);
            return;
        }
        // Se passado na URL "frame=true", não processa o templa nem os componentes
        if ( !empty($_obter['frame']) )
        {
            echo $pagina['content'];
            return;
        }
        // Obtém os componentes
        $components = $this->Pagina_model->obter_componentes($site['id'], $pagina['id']);
        // Obtém o template
        $template = $this->Pagina_model->obter_template($site['template_id'], $pagina['template_id']);

        // template padrão
        if ( $template['cabecalho_rodape_padrao'] == 't' )
        {
            $template_padrao = $this->Pagina_model->obter_template(ID_TEMPLATE_PADRAO);
            
            //retira o google analitics do template padrao se o templete jah tiver
            if( strripos( $template['html_header'] , "Google Analytics") !== false)
            {
                //retira o google analitics do template padrao
                $template_padrao['html'] = preg_replace('/\<!-- Google analytics (.+)(\n)(.+)<script(.+)(\n.+)+<\/script>/im', '',$template_padrao['html']); 
            }

            $template_html = str_replace('{template_do_site}', $template['html'], $template_padrao['html']);
            $template_html = str_replace('{html_header}', $template['html_header'], $template_html);
        }
        else
        {
            $template_html = $template['html'];
        }

        // Substitui alguns parametros do template
        $replace = array();
        $replace['{loadfavicon}'] = $this->_carregar_favicon($site['icone']); // Favicon da página
        $replace['{loadtitle}'] = $this->_carregar_titulo(); // Titulo da página
        $replace['{loadcontenttitle}'] = $this->_carregar_titulo_conteudo(); // Título no conteudo
        $replace['{loadcontent}'] = $pagina['content']; // Conteúdo
        $template_html = strtr($template_html, $replace);

        /*
         * Processa os componentes
         */
        $template_html = $this->replace_components($template_html, $components);

        preg_match_all('/{loadscript ([a-zA-Z0-9_.-\/]+)}/', $template_html, $match_script);

        $replace_scripts = array();
        for ( $x=0; $x < count($match_script[0]); $x++ )
        {
            $replace_scripts[$match_script[0][$x]] = $this->Pagina_model->process_component($match_script[1][$x], '', '');
        }
        $template_html = strtr($template_html, $replace_scripts);
        // Fim dos componentes

        /*
         * Ajeita caminhos dos links, exceto se tem ":" ex.: http: mailto:
         */
        $template_html = preg_replace('/(href|src|background)="([^":]*)"/', '\1="'.$site['url'].'\2"', $template_html);

        // Substitui alguns parametros
        $replace = array();
        $replace['{load ano}'] = date('Y'); // Ano atual
        //FIXME: bug no editor de template
        $replace['{loadtextarea}'] = 'textarea'; // textarea
        $replace['{loadsite_url}'] = base_url(); // Url da página
        $replace['{loadbase_url}'] = base_url(); // Url da página
        $template_html = strtr($template_html, $replace);

        $view['html'] = $template_html;

        $this->load->view('pagina', $view);
    }

    function replace_components($template_html='', $components=array())
    {
        // Obtém os parâmetros passados no loadposition ({loadposition componente/parametro:valor})
        preg_match_all('/{loadposition ([a-z0-9_.-]+)\/?\s*([a-z0-9_.-:\?&=]+)?}/', $template_html, $match_position);
        $components_html = array();
        foreach ( $match_position[1] as $k => $position )
        {
            $param_pagina = '';
            if ( !empty($components[$position]) )
            {
                foreach ( $components[$position] as $component )
                {
                    // Caso esteja passando parâmetros pelo loadposition ({loadposition componente/parametro:valor})
                    if ( !empty($match_position[2][$k]) )
                    {
                        $param_pagina = $match_position[2][$k];
                    }

                    $components_html[$position][] = $this->Pagina_model->process_component($component['path'], $component['content'], $param_pagina);
                }
            }
        }

        $replace_components = array();
        for ( $x=0; $x < count($match_position[0]); $x++ )
        {
            $replace_components[$match_position[0][$x]] = (!empty($components_html[$match_position[1][$x]])?implode('',$components_html[$match_position[1][$x]]):'');
        }

        $template_html = strtr($template_html, $replace_components);
        preg_match_all('/{loadposition ([a-z0-9_.-]+)\/?\s*([a-z0-9_.-:\?&=]+)?}/', $template_html, $match_position);
        if ( is_array($match_position[1]) && count($match_position[1]) > 0 )
        {
            // Recursividade:
            $template_html = $this->replace_components($template_html, $components);
        }

        return $template_html;
    }

    function _carregar_titulo()
    {
        $titulo = $this->CI->data->site['titulo'];

        if ( $this->CI->data->pagina['url'] != '' )
        {
            $titulo .= ' - ' . $this->CI->data->pagina['titulo'];
        }

        if ( !empty($this->CI->data->titulo) )
        {
            $titulo .= ' - ' . $this->CI->data->titulo;
        }

        return $titulo;
    }

    function _carregar_titulo_conteudo()
    {
        $titulo = $this->CI->data->pagina['titulo_conteudo'];
        if ( !empty($this->CI->data->titulo) )
        {
            $titulo = $this->CI->data->titulo;
        }

        return $titulo;
    }

    function _carregar_conteudo($pagina)
    {
        if ( $pagina['tipo'] == 'componente' )
        {
            $component = unserialize($pagina['content']);
            $pagina['content'] = $this->Pagina_model->process_component($component['path'],$component['params'],'');
        }

        return $pagina['content'];
    }

    /**
     * Carrega o favicon do site
     */
    function _carregar_favicon($icone='')
    {
        $favicon = '../arquivos/icones_dos_sites/2.png';
        if ( (strlen($icone) > 3 ) && is_file(SERVERPATH.'arquivos/icones_dos_sites/'.$icone) )
        {
            $favicon = '../arquivos/icones_dos_sites/'.$icone;
        }

        $favicon_html = '<link href="'.base_url($favicon).'" rel="shortcut icon" type="imagina/vnd.microsoft.icon"/>';

        return $favicon_html;
    }
}
