<?php
// Inclui o controller padrão
include_once(APPPATH . 'controllers/default_controller.php');

/**
 * Controller do módulo de notícias - tabela: cms_noticias.
 */
class Noticias extends Default_controller
{
    // Armazena as mensagem de erros
    private $erros;
    private $path;

    // Limites das imagens
    private $thumb_w = 48;
    private $thumb_h = 48;
    private $image_w = 1024;
    private $image_h = 1024;
    private $max_destaques = 5;

    // Limite de caracteres da introduçao
    private $limiteDeCaracteres = 144;

    // Nome do campo categoria
    private $categoria_titulo = 'Categoria';
    // Se permite mais de uma categoria
    private $permite_mais_de_uma_categoria = true;

    /**
     * Construtor
     * Define tabela, instancia model, etc
     */
    function __construct()
    {
        // Obtém usuário logado
        $this->site_id = $this->session->userdata('site_id');
        $this->site_dir = $this->session->userdata('site_dir');

        // Define a tabela principal deste módulo
        $this->table_name = 'cms_noticias';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Noticias_model');
        $this->Noticias_model->set_table_name($this->table_name);

        // Define o path das imagens
        $this->path = '';
        if ( strlen($this->site_dir) )
        {
            $this->path .= $this->session->userdata('site_dir').'/';
        }
        $this->path .= 'arquivos/';

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Notícias';
        $this->module = '';
        $this->controller = 'noticias';

        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
                'titulo' => 'Editar categorias',
                'atributos_html' => array('class' => 'button search',
                                          'title' => 'Alterar categorias',
                                          'style' => 'float:right;',
                                          'href' => site_url(($this->module?$this->module:'').'/'.$this->controller.'/listar_categorias')
                )
            )
        ); 
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     *
     * @param integer $pagina_atual - Número da página (na listagem)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Where default (somente do site atual)
        $this->where = array('site_id ='.$this->site_id);

        // Aplica um ORDER BY na listagem
        $this->ordens = array('id DESC');

        $this->colunas = array(
            array(
                'descricao' => 'Título',
                'coluna' => 'titulo',
                'coluna_filtravel' => true,
                'tipo' => 'string'
            ),/*
            array(
                'descricao' => $this->categoria_titulo,
                'tamanho' => '170',
                'coluna' => 'categoria',
                'sql' => "(SELECT cat.titulo FROM cms_noticias_categorias cat INNER JOIN cms_noticias_rel_categorias rel ON (rel.categoria_id = cat.id) WHERE noticia_id = cms_noticias.id)",
                'coluna_filtravel' => true,
                'tipo' => 'string'
            ),*/
            array(
                'descricao' => 'Ordem',
                'align' => 'center',
                'coluna' => 'ordem',
                'sql' => '(SELECT ordem FROM cms_noticias_destaques WHERE noticia_id = cms_noticias.id)',
                'html' => $this->obter_html_ordens(),
                'style' => 'cursor:pointer;'
            ),
            array(
                'descricao' => 'Data', // Descrição (texto impresso na tela)
                'tamanho' => '110', // Largura da coluna
                'align' => 'center', // Alinhamento da coluna
                'coluna' => 'data_formatada', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'criado', // Coluna utilizada para ordenar/filtrar
                'sql' => "TO_CHAR(criado, 'DD/MM/YYYY HH24:MI:SS')", // Coluna utilizada no SQL para obter os dados
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'date', // Tipo (integer, date, string, boolean), usado no WHERE...
                'atributos_html' => array(
                    'onkeypress' => 'return mascaraData(this, event);',
                    'onblur' => 'formataData(this);',
                    'maxlength' => '10'
                )
            )
        );

        parent::listar();
    }

    /**
     * Insere/edita uma notícia
     *
     * @param integer $id - Código da notícia a ser editada
     */
    function editar($id='')
    {
        $dados = array();

        // Obtém a notícia se for edição
        if ( strlen($id) > 0 )
        {
            $noticia_old = $this->Noticias_model->obter($id);
            $dados['noticia'] = $noticia_old;
        }

        // Path
        $path = $this->path;
        $dados['path'] = $path;
        // Path temporário
        $path_temporario = '/tmp/noticias/';
        if ( !is_dir($path_temporario) && !mkdir($path_temporario) ){$dados['error'] = 'Falha ao criar diretório temporário('.$path_temporario.').';}
        $path_temporario .= $this->site_id.'/';
        if ( !is_dir($path_temporario) && !mkdir($path_temporario) ){$dados['error'] = 'Falha ao criar diretório temporário('.$path_temporario.').';}
        $path_temporario .= $this->usuario_id.'/';
        if ( !is_dir($path_temporario) && !mkdir($path_temporario) ){$dados['error'] = 'Falha ao criar diretório temporário('.$path_temporario.').';}
        $dados['path_temporario'] = $path_temporario;

        // Obtém dados para os <select>
        $dados['categorias']= $this->Noticias_model->listar_categorias();
        $dados['imagens'] = $this->Noticias_model->obter_imagens($id, $this->usuario_id);

        // Se não tem post
        if ( $this->input->post('submit') )
        {
            // Obtém os dados do form
            $dados['noticia'] = $this->input->post('noticia');
            // Site da notícia
            $dados['noticia']['site_id'] = $this->site_id;
            // Mantém criado e criado_por
            $dados['noticia']['criado'] = $noticia_old['criado'] ? $noticia_old['criado'] : date('Y-m-d H:i:s');
            $dados['noticia']['criado_por'] = $noticia_old['criado_por'] ? $noticia_old['criado_por'] : $this->usuario_id;
            // Gera o alias
            $dados['noticia']['alias'] = generate_url($dados['noticia']['titulo']);
            // Obtém as imagens
            $imagens = array();
            $imgs = (array)glob('/tmp/noticias/'.$this->site_id.'/'.$this->usuario_id.'/'."{*.*}", GLOB_BRACE);
            if ( $this->input->post('imagens') )
            {
                foreach ( $this->input->post('imagens') as $imagem )
                {
                    $img = array();
                    if ( $imagem['id'] )
                    {
                        $img['id'] = $imagem['id'];
                    }
                    else
                    {
                        $img['arquivo'] = $path_temporario.base64_decode(urldecode($imagem['nome_arquivo']));
                    }
                    $img['credito'] = $imagem['credito'];
                    $img['legenda'] = $imagem['legenda'];
                    $img['capa'] = $imagem['capa'] == 1;
                    $imagens[] = $img;
                }
            }
            $dados['noticia']['imagens'] = $imagens;

            // Validação
            $this->form_validation->set_rules('noticia[titulo]', 'Título', 'trim|required');
            $this->form_validation->set_rules('noticia[intro]', 'Descrição', 'trim|required');
            if ( $this->form_validation->run() )
            {
                try
                {
                    // Tenta salvar a notícia
                    if ( $this->Noticias_model->salvar($dados['noticia'], $path, $path_temporario) )
                    {
                        redirect('noticias');
                    }
                    else
                    {
                        $this->erros[] = 'Desculpe, mas não foi possível armazenar o registro.';
                    }
                }
                catch ( Exception $e )
                {
                    $this->erros[] = $e->getMessage();
                }
            }
            else
            {
                $this->erros[] = validation_errors();
            }
        }

        // Se tem mensagem:
        if ( $this->erros )
        {
            $dados['erros'] = $this->erros;
        }

        // Limite de caracteres
        $dados['limiteDeCaracteres'] = $this->limiteDeCaracteres;
        $dados['categoria_titulo'] = $this->categoria_titulo;
        $dados['permite_mais_de_uma_categoria'] = $this->permite_mais_de_uma_categoria;

        $this->load->view('noticias_editar', $dados);
    }

    /**
     * Altera a ordem das notícias
     *
     * @param integer $notícia_id - Código da notícia
     * @param integer $ordem - Ordem de destaque
     */
    function alterar_ordem($noticia_id, $ordem)
    {
        if ( $this->Noticias_model->alterar_ordem($noticia_id, $ordem) )
        {
            redirect('noticias');
        }
        else
        {
            $this->erros[] = 'Não foi possível alterar a ordem desta notícia.';
            $this->editar($noticia_id);
        }
    }

    /**
     * Função chamada por AJAX para exibir as imagens da notícia
     *
     * @param integer $noticia_id - Código da notícia
     */
    function ajax_obter_imagens($noticia_id='')
    {
        // Paths
        $path_temporario = '/tmp/noticias/'.$this->site_id.'/'.$this->usuario_id.'/';
        $imagens_temporarias = glob("$path_temporario{*.*}", GLOB_BRACE);

        $html = '';
        if ( is_array($imagens_temporarias) && count($imagens_temporarias) > 0 )
        {
            foreach ( $imagens_temporarias as $i => $imagem )
            {
                $img = urlencode(base64_encode(array_pop(explode('/', $imagem))));
                $html .= '<div id="imagem_tmp_'.$i.'" class="caixa_imagem">';
                $html .= '<div style="background-image:url('.site_url('noticias/ler_imagem_temporaria/'.$img).');" class="imagem">';
                $html .= '<img src="'.site_url('../arquivos/css/icons/delete.png').'" class="remover_imagem" id="remover_tmp_'.$i.'" title="Clique para remover esta imagem" onclick="remover_imagem_temporaria(\''.$i.'\', \''.$img.'\');" />';
                $html .= '<input type="hidden" name="imagens['.$i.'][nome_arquivo]" value="'.$img.'" />';
                $html .= '</div>';
                $html .= '<br>';
                $html .= '<!--input type="text" id="credito_'.$i.'" name="imagens['.$i.'][credito]" placeholder="Crédito desta imagem" />';
                $html .= '<br>';
                $html .= '<input type="text" id="legenda_'.$i.'" name="imagens['.$i.'][legenda]" placeholder="Legenda desta imagem" /-->';
                $html .= '<br>';
                $html .= '<input type="checkbox" id="capa_'.$i.'" name="imagens['.$i.'][capa]" value="1" class="capa" title="Marque para definir esta imagem como capa"/><label for="capa_'.$i.'">Imagem capa</label>';
                $html .= '</div>';
            }
        }
        else
        {
            $html = '<small>Não há nenhuma imagem temporária.</small>';
        }

        echo $html;
    }

    /**
     * Lê uma imagem temporária
     *
     * @param integer $i - Número da ordem no glob em path_temp.
     * @return image
     */
    function ler_imagem_temporaria($img)
    {
        // Paths
        $path_temporario = '/tmp/noticias/'.$this->site_id.'/'.$this->usuario_id.'/';
        $img = base64_decode(urldecode($img));
        $imagem = $path_temporario.$img;

        if ( is_file($imagem) )
        {
            $ext = strtolower(array_pop(explode('.', $imagem)));
            if($ext=='jpg'){$ext ='jpeg';}

            header('Content-Type: image/'.$ext);
            readfile($imagem);
        }
        else
        {
            header('Content-Type: image/jpeg'); 
            readfile(SERVERPATH.'arquivos/imagens/imagem-indisponivel.jpg');
        }
    }

    /**
     * Ajax que remove uma imagem temporária
     *
     * @param integer $i - Número da ordem no glob em path_temp.
     * @return 1(sucesso) ou 0(falha)
     */
    function ajax_remover_imagem_temporaria($img)
    {
        // Paths
        $path_temporario = '/tmp/noticias/'.$this->site_id.'/'.$this->usuario_id.'/';
        $img = base64_decode(urldecode($img));
        $imagem = $path_temporario.$img;

        $ok = (is_file($imagem) && !unlink($imagem)) ? '0' : '1';

        echo $ok;
    }

    /**
     * Ajax que remove uma imagem da notícia
     *
     * @param integer $id - Código da imagem
     * @return 1(sucesso) ou 0(falha)
     */
    function ajax_remover_imagem($id)
    {
        $ok = $this->Noticias_model->remover_imagem($id) ? '1' : '0';

        echo $ok;
    }

    /**
     * Obtém o HTML para a coluna Ordem na listagem
     */
    function obter_html_ordens()
    {
        $html = <<<HTML
<script type="text/javascript" language="JavaScript">
$("td.html_id_#id#").click(function()
{
    var id = #id#;
    div_ordens = document.getElementById('noticias'+id);
    div_ordens.style.display='block';
});
</script>
<div style="display:none;" id="noticias#id#">
HTML;
        for ( $i=1; $i <= $this->max_destaques; $i++ )
        {
            $html .= ' <a href="'.site_url('../cms/noticias/alterar_ordem/#id#/'.$i).'">['.$i.']</a> ';
        }
        $html .= ' <a href="'.site_url('../cms/noticias/alterar_ordem/#id#/'.$i).'">[...]</a>';
        $html .= '<div>';

        return $html;
    }

    /**
     * Reescrita função de remover notícia para passar pela model onde remove dependências
     *
     * @param integer $id - Código da notícia
     */
    function remover($id)
    {
        try
        {
            // Apaga a notícia
            if ( $this->Noticias_model->remover($id) )
            {
                redirect('noticias');
            }
        }
        catch( Exception $e )
        {
            $this->erros[] = $e->getMessage();
            $this->editar($noticia_id);
        }
        
    }

    /**
     * Função que lista as categorias
     *
     * @param integer $pagina_atual - Número da página (na listagem)
     */
    function listar_categorias()
    {
        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
                'titulo' => 'Voltar para notícias',
                'atributos_html' => array('class' => 'button search',
                                          'title' => 'Alterar notícias',
                                          'style' => 'float:right;',
                                          'href' => site_url(($this->module?$this->module:'').'/'.$this->controller.'/listar')
                )
            )
        ); 

        // Troca para _categorias
        $this->table_name = 'cms_noticias_categorias';
        $this->Noticias_model->set_table_name($this->table_name);
        unset($this->colunas_default[parent::COLUNA_ATIVO]);
        $this->desabilitar_paginacao = true;
        $this->desabilitar_buscar = true;
        $this->desabilitar_ordenacao = true;
        $this->funcao_inserir = 'inserir_categoria';
        $this->funcao_editar = 'editar_categoria';
        $this->funcao_remover = 'remover_categoria';
        $this->acoes[self::ACAO_EDITAR]['acao'] = $this->funcao_editar;
        $this->acoes[self::ACAO_REMOVER]['acao'] = $this->funcao_remover;

        $this->registros = $this->Noticias_model->listar_categorias(true);

        // Aplica um ORDER BY na listagem
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título',
                'coluna' => 'titulo',
                'coluna_filtravel' => true,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Categoria pai',
                'coluna' => 'categoria_pai'
            )
        );

        parent::listar();
    }

    function inserir_categoria()
    {
        $this->editar_categoria();
    }

    function editar_categoria($categoria_id=null)
    {
        $dados = array();
        $dados['categorias']= $this->Noticias_model->listar_categorias();

        if ( isset($categoria_id) )
        {
            $dados['categoria'] = $this->Noticias_model->obter_categoria($categoria_id);
        }

        if ( $this->input->post('submit') )
        {
            $dados_old = $this->Noticias_model->obter_categoria($categoria_id);

            // Obtém os dados do form
            $dados['categoria'] = $this->input->post('categoria');

            // Se não tem site_id
            if ( !$dados['categoria']['site_id'] )
            {
                // Verifica se antes tinha
                if ( $dados_old['site_id'] )
                {
                    $dados['categoria']['site_id'] = $dados_old['site_id'];
                }
                else
                {
                    // Pega o site atual
                    $dados['categoria']['site_id'] = $this->site_id;
                }
            }

            // Se não tem pai, define como NULL
            if ( strlen($dados['categoria']['parent_id']) == 0 )
            {
                $dados['categoria']['parent_id'] = null;
            }

            // Validação
            $this->form_validation->set_rules('categoria[titulo]', 'Título', 'trim|required');
            if ( $this->form_validation->run() )
            {
                // Se for o site da Univates e não escolheu editorial
                try
                {
                    // Tenta salvar a notícia
                    $ok = $this->Noticias_model->salvar_categoria($dados['categoria']);
                    if ( $ok )
                    {
                        redirect('noticias/listar_categorias');
                    }
                    else
                    {
                        $dados['erros'] = 'Desculpe, mas não foi possível armazenar o registro.';
                    }
                }
                catch ( Exception $e )
                {
                    $dados['erros'] = $e->getMessage();
                }
            }
            else
            {
                $dados['erros'] = validation_errors();
            }
        }

        $this->load->view('noticias_categorias', $dados);
    }

    function remover_categoria($categoria_id)
    {
        if ( $this->Noticias_model->remover_categoria($categoria_id) )
        {
            redirect('noticias/listar_categorias');
        }
        else
        {
            $this->editar_categoria($categoria_id);
        }
    }
}
?>
