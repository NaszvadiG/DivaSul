<?php
// Inclui a classe default_controller.php (não conseguimos por no autoload.)
include_once(APPPATH . 'controllers/default_controller.php');
class Paginas extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'cms_paginas';
        $this->tem_hierarquia = true;
        $this->desabilitar_ordenacao = true;
        $this->exibir_coluna_ordem = true;
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Paginas_model');
        $this->Paginas_model->set_table_name($this->table_name);

        /**
         * Adiciona link para a página
         */
        // Altera o tamnho da coluna das ações
        $this->colunas_default[parent::COLUNA_ACOES]['tamanho'] = 75;
        $this->acoes = array_merge(array(
            array(
                'descricao' => 'Visualizar',
                'acao' => 'visualizar', // Função do controller que será chamada (ação)
                'icone' => 'arquivos/css/icons/internet.png',
                'target' => '_blank'
            )
        ), $this->acoes);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Páginas';
        $this->module = '';
        $this->controller = 'paginas';
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Aplica um WHERE na listagem
        $this->where = array();
        // Somente do site em questão
        $this->where[] = 'site_id = '.$this->session->userdata('site_id');

        // Aplica um ORDER BY na listagem
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'titulo',
                'sql' => "titulo || '<br><small>' || url || '</small>'",
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string', // Tipo (integer, date, string, boolean), usado no WHERE...
                'linkar_para_edicao' => true // Coloca link para a edição nesta coluna
            ),
            array(
                'descricao' => 'Tipo',
                'coluna' => 'tipo',
                'tipo' => 'string',
                'tamanho' => '175',
                'concatenar_com' => 'content',
                'passar_funcao' => 'unserialize',
                'condicao_concatenar' => '=',
                'comparar_com' => 'componente'
            ),
            array(
                'coluna' => 'content',
                'oculta' => true
            ),
            array(
                'descricao' => 'Menu',
                'coluna' => 'menu',
                'coluna_sql' => 'categoria',
                'sql' => '(SELECT titulo FROM cms_menus WHERE id = cms_paginas.menu_id)',
                'tamanho' => '75'
            )
        );

        parent::listar();
    }

    function editar($id=NULL)
    {
        $dados = array();
        $pagina = array();

        if ( $this->input->post('submit') )
        {
            $pagina = $this->input->post('pagina');
            if ( strlen($id) > 0 )
            {
                $pagina['id'] = $id;
            }
        }
        elseif ( strlen($id) > 0 )
        {
            $pagina = $this->Paginas_model->obter($id);
            if ( $pagina['tipo'] != 'html' )
            {
                $pagina['componente'] = unserialize($pagina['content']); 
            }
            $pagina['url'] = array_pop(explode('/', $pagina['url']));
        }

        // Menus
        $dados['menus'] = $this->Paginas_model->obter_menus();

        // Templates
        $dados['templates'] = $this->Paginas_model->obter_templates();

        // Tipos
        $dados['tipos'] = $this->Paginas_model->listar_tipos();

        $dados['parent'] = array();
        if ( strlen($pagina['parent_id']) > 0 )
        {
            $dados['parent'] = $this->Paginas_model->obter($pagina['parent_id']);
        }

        // Content
        $dados['content_container'] = $this->ajax_obter_content_container($pagina['tipo'], $pagina['content'], true);

        // Validação
        $this->form_validation->set_rules('pagina[titulo]', 'Título', 'trim|required|unique_pagina_titulo');
        $this->form_validation->set_rules('pagina[url]', 'Url do Título', 'regex_match[/^[a-zA-Z0-9_-]*$/]|unique_pagina_url');
        $this->form_validation->set_rules('pagina[ordem]', 'Ordem', 'numeric');
        if ( $this->form_validation->run() )
        {
            $pagina['parent_id'] = ($pagina['parent_id'] == '') ? NULL : $pagina['parent_id'];
            $pagina['menu_id'] = ($pagina['menu_id'] == '') ? NULL : $pagina['menu_id'];

            // Se não tem erros salva o registro
            $pagina['site_id'] = $this->session->userdata('site_id');
            $pagina['url'] = $this->_generate_pagina_url($pagina['titulo']);
            if ( $pagina['tipo'] != 'html' )
            {
                $pagina['content'] = serialize($pagina['content']);
            }
            $id = $this->Paginas_model->salvar($pagina);
            if ( strlen($id) > 0 )
            {
                redirect('paginas');
            }
            else
            {
                $dados['erro'] = 'Desculpe, mas não foi possível inserir o registro.';
            }
        }
        else
        {
            $dados['erro'] = validation_errors();
        }

        $dados['pagina'] = $pagina;

        $this->load->view('paginas_editar', $dados);
    }

    /**
     * Abre o link em nova aba
     */
    function visualizar($id)
    {
        $pagina = $this->Paginas_model->obter($id, array('titulo', 'url', 'site_id'));
        $this->load->model('Sites_model');
        $site_url = current($this->Sites_model->obter($pagina['site_id'], array('dir')));

        $url = '../';
        if ( strlen($site_url) > 0 )
        {
            $url .= $site_url.'/';
        }
        $url .= $pagina['url'];

        echo 'Redirecionando para: <a href="'.site_url($url).'">'.$pagina['titulo'].'</a>';
        header('Location: '.site_url($url));
    }

    function _generate_pagina_url($titulo)
    {
        $pagina = $this->input->post('pagina');
        $url = '';

        if ( ((int)$pagina['parent_id'] > 0) )
        {
            $colunas = array('id, url');
            $pai = $this->Paginas_model->obter($pagina['parent_id'], $colunas);
            if ( strlen($pagina['url']) == 0 )
            {
                $url = generate_url($titulo);
            }
            else
            {
                $url = $pagina['url'];
            }
            $separator = ($pai['url'] != '' ? '/' : '');
            $url = $pai['url'].$separator.$url;
        }

        return $url;
    }

    function get_last_content($id)
    {
        $pagina = $this->Paginas_model->obter($id, array('content_last'));
        echo $pagina['content_last'];
    }

    function baixar_html_pagina($pagina_id)
    {
        $this->load->model('Paginas_model');
        $pagina = $this->Paginas_model->obter($pagina_id);

        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$pagina['titulo'].'.html"');
        echo $pagina['html'];
    }

    function ajax_set_parent()
    {
        // Aplica um WHERE na listagem
        $this->where = array();
        $this->where[] = 'parent_id IS NULL';
        $this->where[] = 'site_id = '.$this->session->userdata('site_id');
        $this->where[] = "ativo = '1'";
        $this->tem_hierarquia = true;

        // Aplica um ORDER BY na listagem
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'titulo',
                'sql' => "titulo || '<br><small>' || url || '</small>'",
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string', // Tipo (integer, date, string, boolean), usado no WHERE...
                'link' => true
            )
        );

        unset($this->colunas_default[self::COLUNA_ORDEM]);
        unset($this->colunas_default[self::COLUNA_ATIVO]);
        unset($this->colunas_default[self::COLUNA_ACOES]);
        $this->view = 'paginas_selecao_pagina_pai';
        $this->view_linha = 'paginas_selecao_pagina_pai_linha';
        
        parent::listar();
    }
    
    function ajax_obter_content_container($tipo, $dados=null, $return=false)
    {
        $content = (is_null($dados)) ? $_POST['content'] : $dados;

        if ( preg_match('/^a:\d+(.*(\n)?){1,};}$/im', $content) )
        {
            $componente = unserialize($content);
        }

        $html = '';
        switch ( $tipo )
        {
            case 'link':  
                $html = 'Link: <input type="text" name="componente[link]" value="'.(!empty($componente['link'])?$componente['link']:'').'" size="60" /><br />
                <label><input type="checkbox" name="componente[blank]" value="1"'.(!empty($componente['blank'])?' checked="checked"':'').' /> Nova janela</label>'; 
            break;
            case 'separador': 
                $html = '<label><input type="checkbox" name="componente[show_titulo]" value="1"'.(!empty($componente['show_titulo'])?' checked="checked"':'').' /> Mostrar título?</label>'; 
            break;
            case 'componente':
                $cparams = (!empty($componente['params'])?$componente['params']:'');
                $cpath = (!empty($componente['path'])?$componente['path']:'');
                $html = 'componente:<br />' . form_input('componente[path]',$cpath) . '<br />Parâmetros:<br />' . form_textarea(array('name'=>'componente[params]','value'=>$cparams,'rows'=>'5','cols'=>'40'));
            break;
            default:  
                $html = '<textarea cols="90" rows="30" id="pagina" name="pagina[content]">'.$content.'</textarea>'; 
            break;
        }

        if ( $return )
        {
            return $html;
        }
        else
        {
            echo $html;
        }
    }
}
?>