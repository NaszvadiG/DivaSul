<?php
include_once(APPPATH . 'controllers/default_controller.php');
class Produtos extends Default_controller
{
    private $path;
    private $path_temporario;

    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'site_produtos';
        $this->exibir_coluna_ordem = true;
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Produtos_model');
        $this->load->model('Cidades_model');

        // Define o path das imagens
        $this->path = 'arquivos/produtos/';

        // Path temporário
        $this->path_temporario = '/tmp/produtos_'.$this->usuario_id.'/';
        if ( !is_dir($this->path_temporario) && !mkdir($this->path_temporario) )
        {
            $dados['error'] = 'Falha ao criar diretório temporário (produtos).';
        }
    
        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
               'titulo' => 'Categorias',
               'atributos_html' => array(
                   'class' => 'button search',
                   'title' => 'Listar tipos de produtos',
                   'style' => 'float:right;',
                   'href' => base_url('site/produtos/listar_categorias')
               )
            )
        );

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Produtos';
        $this->module = 'site';
        $this->controller = 'produtos';
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;
        $this->registros_por_pagina = 10;

        // Aplica um ORDER BY na listagem
        $this->ordens = array('id DESC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Foto Principal</a><br><small><small>clique na foto para ampliar/reduzir</small></small><a>', // Descrição (texto impresso na tela)
                'coluna' => 'foto_capa',
                'coluna_filtravel' => false,
                'align' => 'center',
                'ajax' => 'obter_foto_capa'
            ),
            array(
                'descricao' => 'Categoria', // Descrição (texto impresso na tela)
                'coluna' => 'categoria', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'categorias', // Coluna utilizada para ordenar/filtrar
                'sql' => '(SELECT titulo FROM site_produtos_categorias WHERE id = site_produtos.categoria_id)',
                'coluna_filtravel' => true,
                'tamanho' => 45
            ),
            array(
                'descricao' => 'Referência', // Descrição (texto impresso na tela)
                'coluna' => 'referencia', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true,
                'linkar_para_edicao' => true,
                'tipo' => 'string'
            )
        );

        $this->acoes[1] = array(
            'descricao' => 'Visualizar', // Descrição
            'acao' => 'visualizar', // Função do controller que será chamada (ação)
            'icone' => 'arquivos/css/icons/search.png' // Imagem do botão
        );

        parent::listar();
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar_categorias()
    {
        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
               'titulo' => 'Voltar para produtos',
               'atributos_html' => array(
                   'class' => 'button search',
                   'title' => 'Voltar para produtos',
                   'style' => 'float:right;',
                   'href' => base_url('site/produtos/listar')
               )
            )
        );

        // Desabilita paginacao
        $this->exibir_coluna_ordem = false;
        $this->desabilitar_paginacao = true;
        $this->desabilitar_buscar = true;
        $this->titulo = 'Categorias de produtos';
        $this->funcao_inserir = 'inserir_categoria';
        $this->funcao_editar = 'editar_categoria';
        $this->funcao_remover = 'remover_categoria';
        $this->acoes[self::ACAO_EDITAR]['acao'] = $this->funcao_editar;
        $this->acoes[self::ACAO_REMOVER]['acao'] = $this->funcao_remover;
        $this->funcao_ativar_inativar = 'ativar_inativar_categoria';

        // Define a tabela principal deste módulo
        $this->table_name = 'site_produtos_categorias';
        $this->Produtos_model->set_table_name($this->table_name);

        // Aplica um ORDER BY na listagem
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Categoria Pai', // Descrição (texto impresso na tela)
                'coluna' => 'pai', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'parent_id', // Coluna utilizada para ordenar/filtrar
                'sql' => "COALESCE((SELECT titulo FROM site_produtos_categorias WHERE id = site_produtos_categorias.parent_id),'-')",
                'coluna_filtravel' => true,
                'tamanho' => 45
            )
        );

        $dados = array();
        $dados['function'] = 'listar_categorias';
        parent::listar($dados);
    }

    function editar($id=null)
    {
        // Carrega a model
        $this->load->model('Produtos_model');

        // Array de dados para a view
        $dados = array();

        // Obtém os dados
        if ( $this->input->post('submit') )
        {
            // se tem post, obtém do formulário
            $dados = $this->input->post();
        }
        elseif ( (int)$id > 0 )
        {
            // se tem id, obtém da base
            $dados['produto'] = $this->Produtos_model->obter($id);
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Validação
            $this->form_validation->set_rules('produto[categoria_id]', 'Categoria', 'trim|required');
            $this->form_validation->set_rules('produto[titulo]', 'Título', 'trim|required');
            $this->form_validation->set_rules('produto[descricao]', 'Descrição', 'trim|required');
            $this->form_validation->set_rules('produto[ativo]', 'Ativo', 'trim|required');
            if ( $this->form_validation->run() )
            {
                $dados['produto']['link'] = str_replace('-', '_', MY_Utils::removeSpecialChars(strtolower(utf8_decode($dados['produto']['titulo']))));
                $dados['produto']['link'] = preg_replace('/_{2,}/','_',$dados['produto']['link']);

                $ok = $this->Produtos_model->salvar($dados['produto']);
                $dados['produto']['id'] = $ok;
                $path_destino_imagens = SERVERPATH.$this->path.$dados['produto']['id'].'/';
                // Se existe procede, se não exibe erro!
                if ( is_dir(SERVERPATH.$this->path.$dados['produto']['id']) || mkdir(SERVERPATH.$this->path.$dados['produto']['id']) )
                {
                    if ( $ok )
                    {
                        // Obtém as imagens do diretório temporário:
                        $imagens_temporarias = glob("$this->path_temporario{*.*}", GLOB_BRACE);

                        // percorre o array de img temp
                        if ( is_array($imagens_temporarias) && count($imagens_temporarias) > 0 )
                        {
                            foreach ( $imagens_temporarias as $img )
                            {
                                // Redimensiona a imagem
                                $imagem = array(
                                    'name' => generate_url(current(explode('.', basename($img)))).'.'.MY_Utils::obter_extensao_imagem($img),
                                    'tmp_name' => $img
                                );
                                MY_Utils::redimensionar_imagem($imagem, $path_destino_imagens.$imagem['name'], 1280, 1280);

                                // Redimensiona a miniatura
                                $imagem_thumb = array(
                                    'name' => generate_url(current(explode('.', basename($img)))).'_thumb.'.MY_Utils::obter_extensao_imagem($img),
                                    'tmp_name' => $img
                                );
                                MY_Utils::redimensionar_imagem($imagem_thumb, $path_destino_imagens.$imagem_thumb['name'], 168, 168);
                                if ( strlen($dados['produto']['foto_capa']) == 0 )
                                {
                                    $dados['produto']['foto_capa'] = $imagem_thumb['name'];
                                    $ok = $this->Produtos_model->salvar($dados['produto']);
                                }

                                // Apaga a imagem temporária
                                if ( !unlink($img) )
                                {
                                    $dados['erro'] = 'Não foi possível apagar a imagem temporária '.$img.'.';
                                }

                                $cont++;
                            }
                        }
                        // Apaga o diretório temporário
                        if ( !rmdir($this->path_temporario) )
                        {
                            $dados['erro'] = 'Não foi possível apagar o diretório temporário.';
                        }

                        redirect('site/produtos');
                    }
                    else
                    {
                        $dados['erro'] = 'Não foi possível salvar o registro.';
                    }
                }
                else
                {
                    $dados['erro'] = 'Não foi possível criar o diretório destino das imagens.';
                }
            }
            else
            {
                if ( rtrim(trim(strip_tags(validation_errors()))) == 'Unable to access an error message corresponding to your field name.' )
                {
                    $dados['erro'] = 'O título deve ser único. Este título já está em uso.';
                }
                else
                {
                    $dados['erro'] = validation_errors();
                }
            }
        }

        $dados['path'] = $this->path;
        $dados['path_temporario'] = $this->path_temporario;

        $dados['categorias'] = array();
        $categorias = $this->Produtos_model->listar_categorias();
        foreach ( $categorias as $categoria )
        {
            $dados['categorias'][$categoria['id']] = $categoria['titulo'];
        }

        $dados['cidades'] = array();
        $cidades = $this->Cidades_model->listar();
        foreach ( $cidades as $cidade )
        {
            $dados['cidades'][$cidade['id']] = $cidade['nome'];
        }

        $dados['titulo'] = $this->titulo;
        $dados['module'] = $this->module;
        $dados['controller'] = $this->controller;

        $this->load->view('produtos_editar', $dados);
    }

    function inserir_categoria()
    {
        $this->editar_categoria();
    }
    function editar_categoria($id=null)
    {
        $imagem = $_FILES['imagem'];
        $icone = $_FILES['icone'];

        $this->path = SERVERPATH.'arquivos/tipos-produtos/';
        if ( !is_dir($this->path) )
        {
            // Se não existe, cria-o
            mkdir($this->path);
        }
    
        // Carrega a model
        $this->table_name = 'site_produtos_categorias';
        $this->Produtos_model->set_table_name($this->table_name);

        // Array de dados para a view
        $dados = array();

        // Obtém os dados
        if ( $id )
        {
            // se não tem post e tem id, obtém da base de dados
            $dados['categoria'] = $categoria_old = $this->Produtos_model->obter($id);
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Se existe procede, se não exibe erro!
            $dados = $this->input->post();

            // Validação
            $this->form_validation->set_rules('categoria[titulo]', 'Título', 'trim|required');
            $this->form_validation->set_rules('categoria[ativo]', 'Ativo', 'trim|required');
            if ( $this->form_validation->run() )
            {
                $id = $this->Produtos_model->salvar($dados['categoria']);
                if ( $id )
                {
                    if ( !$icone['tmp_name'] && strlen($categoria_old['icone']) == 0 )
                    {
                        //$dados['erro'] = 'Você precisa enviar um ícone.';
                    }
                    elseif ( !$imagem['tmp_name'] && strlen($categoria_old['arquivo']) == 0 )
                    {
                        //$dados['erro'] = 'Você precisa enviar uma imagem.';
                    }
                    else
                    {
                        if ( !$icone['tmp_name'] )
                        {
                            $dados['categoria']['icone'] = $categoria_old['icone'];
                        }
                        if ( !$imagem['tmp_name'] )
                        {
                            $dados['categoria']['arquivo'] = $categoria_old['arquivo'];
                        }
                        // O nome das imagens é um contador, se já existem imagens, continua o contador
                        $imagens = glob($this->path.$id."/{*.*}", GLOB_BRACE);
                        if ( $imagens )
                        {
                            foreach ( $imagens as $img )
                            {
                                if ( ($icone['tmp_name'] || (array_pop(explode('/', $img)) != $dados['categoria']['icone'])) &&
                                     ($imagem['tmp_name'] || (array_pop(explode('/', $img)) != $dados['categoria']['arquivo']))
                                   )
                                {
                                    unlink($img);
                                }
                            }
                        }

                        // Se existe procede, se não exibe erro!
                        if ( is_dir(SERVERPATH.'arquivos/tipos-produtos') || mkdir(SERVERPATH.'arquivos/tipos-produtos') )
                        {
                            if ( is_dir(SERVERPATH.'arquivos/tipos-produtos/'.$id.'/') || mkdir(SERVERPATH.'arquivos/tipos-produtos/'.$id.'/') )
                            {
                                if ( $icone['tmp_name'] )
                                {
                                    //Ícone
                                    if ( substr($icone['name'], 0, 6) != 'icone_' )
                                    {
                                        $icone['name'] = 'icone_'.$icone['name'];
                                    }
                                    MY_Utils::redimensionar_imagem($icone, SERVERPATH.'arquivos/tipos-produtos/'.$id.'/'.$icone['name'], 224, 224);
                                    if ( !is_file(SERVERPATH.'arquivos/tipos-produtos/'.$id.'/'.$icone['name']) )
                                    {
                                        $dados['erro'] = 'Falha ao redimensionar icone: "'.SERVERPATH.'arquivos/tipos-produtos/'.$id.'/'.$icone['name'].'".';
                                    }
                                    $dados['categoria']['icone'] = $icone['name'];
                                }

                                if ( $imagem['tmp_name'] )
                                {
                                    //Imagem
                                    if ( substr($imagem['name'], 0, 7) != 'imagem_' )
                                    {
                                        $imagem['name'] = 'imagem_'.$imagem['name'];
                                    }
                                    MY_Utils::redimensionar_imagem($imagem, SERVERPATH.'arquivos/tipos-produtos/'.$id.'/'.$imagem['name'], 224, 224);
                                    if ( !is_file(SERVERPATH.'arquivos/tipos-produtos/'.$id.'/'.$imagem['name']) )
                                    {
                                        $dados['erro'] = 'Falha ao redimensionar imagem: "'.SERVERPATH.'arquivos/tipos-produtos/'.$id.'/imagem_'.$imagem['name'].'".';
                                    }
                                   $dados['categoria']['arquivo'] = $imagem['name'];
                               }
                            }
                            else
                            {
                                $dados['erro'] = 'Falha ao criar diretório "arquivos/tipos-produtos/'.$id.'".';
                            }
                        }
                        else
                        {
                            $dados['erro'] = 'Falha ao criar diretório "arquivos/tipos-produtos".';
                        }
                    }

                    if ( strlen($dados['erro']) == 0 )
                    {
                        $dados['categoria']['id'] = $id;
                        if ( $dados['categoria']['parent_id'] == 0 )
                        {
                            unset($dados['categoria']['parent_id']);
                        }

                        $this->Produtos_model->salvar($dados['categoria']);
                        redirect('site/produtos/listar_categorias');
                    }
                }
                else
                {
                    $dados['erro'] = 'Não foi possível salvar o registro.';
                }
            }
            else
            {
                $dados['erro'] = validation_errors();
            }
        }

        $dados['categorias'] = $this->Produtos_model->listar_categorias();

        $this->load->view('produtos_categorias', $dados);
    }

    /**
     * Ação da coluna ativo
     */
    function ativar_inativar_categoria($id)
    {
        // Carrega a model
        $this->table_name = 'site_produtos_categorias';
        $this->Produtos_model->set_table_name($this->table_name);

        $categoria = $this->Produtos_model->obter($id);
        if ( $categoria['ativo'] == 1 )
        {
            $categoria['ativo'] = 0;
        }
        else
        {
            $categoria['ativo'] = 1;
        }

        if ( $this->Produtos_model->salvar($categoria) )
        {
            redirect('site/produtos/listar_categorias');
        }
        else
        {
            $this->editar_categoria($id);
        }
    }

    /**
     * Remove uma categoria de produto
     * @param $id Código do produto
     */
    function remover_categoria($id)
    {
        // Carrega a model
        $this->table_name = 'site_produtos_categorias';
        $this->Produtos_model->set_table_name($this->table_name);

        if ( $this->Produtos_model->remover($id) )
        {
            redirect('site/produtos/listar_categorias');
        }
        else
        {
            $this->editar_categoria($id);
        }
    }

    /**
     * Remove um produto
     * @param $id Código do produto
     */
    function remover($id)
    {
        foreach ( (array)glob($this->path.$id."/{*.*}", GLOB_BRACE) as $imagem )
        {
            $ok = @unlink($imagem);
        }
        $ok = @rmdir($this->path.$id);

        if ( !is_dir($this->path.$id) )
        {
            parent::remover($id);
            redirect('site/produtos');
        }
        else
        {
            $dados = array();
            $dados['erro'] = 'Não foi possível remover as imagens.';
            $this->load->view('produtos_editar', $dados);
        }
    }

    /**
     * Remove uma imagem
     * @param $nome Nome da imagem
     */
    function remover_imagem($id, $nome)
    {
        $imagem = SERVERPATH.$this->path.$id.'/'.$nome;
        $miniatura = SERVERPATH.$this->path.$id.'/'.str_replace('_thumb', '', $nome);

        if ( (!is_file($imagem) || unlink($imagem)) && (!is_file($miniatura) || unlink($miniatura)) )
        {
            echo '1';
        }
        else
        {
            echo 'Não foi possível remover a imagem.';
        }
    }

    /**
     * Retorna a imagem capa para a listagem de produtos
     * @param $id Código da Produto
     */
    function obter_foto_capa($id)
    {
        $produto = $this->Produtos_model->obter($id);
        $miniatura = base_url('../arquivos/produtos/'.$id.'/'.$produto['foto_capa']);
        $imagem = base_url('../arquivos/produtos/'.$id.'/'.str_replace('_thumb','',$produto['foto_capa']));
        echo '<div style="height:55px;position:relative;background-repeat:no-repeat;background-position:center;background-size:cover;background-image:url(\''.$miniatura.'\')" ';
        echo '     title="Clique para ampliar" ';
        echo '     onclick="if($(\'#foto_produto_'.$id.'\').css(\'display\')==\'none\'){$(\'.thumb_produtos\').hide();$(\'#foto_produto_'.$id.'\').show()}else{$(\'#foto_produto_'.$id.'\').hide()}"> ';
          echo '<div id="foto_produto_'.$id.'" ';
          echo '     class="thumb_produtos" ';
          echo '     title="Clique para reduzir" ';
          echo '     style="display:none;position:absolute;left:-90%;top:-90%;z-index:'.(999999999999-$id).';height:350px;width:350px;background-repeat:no-repeat;background-position:center;background-size:cover;background-image:url(\''.$imagem.'\')"> ';
          echo '</div>';
        echo '</div>';
    }

    /**
     * Direciona para o anúncio do Imóvel
     * @param $id Código da Imóvel
     */
    function visualizar($id)
    {
        $produto = $this->Produtos_model->obter($id);

        redirect(base_url('../produto/'.$produto['id'].'/'.$produto['link']));
    }

    /**
     * Função chamada por AJAX para exibir as imagens da notícia
     *
     * @param integer $noticia_id - Código da notícia
     */
    function ajax_obter_imagens($noticia_id='')
    {
        // Paths
        $imagens_temporarias = glob("$this->path_temporario{*.*}", GLOB_BRACE);

        $html = '';
        if ( is_array($imagens_temporarias) && count($imagens_temporarias) > 0 )
        {
            foreach ( $imagens_temporarias as $i => $imagem )
            {
                $img = urlencode(base64_encode(array_pop(explode('/', $imagem))));
                $html .= '<div id="imagem_tmp_'.$i.'" class="caixa_imagem">';
                $html .= '<label for="capa_'.$i.'">';
                $html .= '<div style="background-image:url('.site_url('../cms/site/produtos/ler_imagem_temporaria/'.$img).');" class="imagem">';
                $html .= '<img src="'.site_url('../arquivos/css/icons/delete.png').'" class="remover_imagem" id="remover_tmp_'.$i.'" title="Clique para remover esta imagem" onclick="remover_imagem_temporaria(\''.$i.'\', \''.$img.'\');" />';
                $html .= '<input type="hidden" name="imagens['.$i.'][nome_arquivo]" value="'.$img.'" />';
                $html .= '</div>';
                $html .= '</label>';
                $html .= '<br>';
                $html .= '<input type="checkbox" data-id="'.basename($imagem).'" id="capa_'.$i.'" name="imagens['.$i.'][capa]" value="1" class="capa" title="Marque para definir esta imagem como capa"/>';
                $html .= '<label for="capa_'.$i.'">Imagem capa</label>';
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
        $img = base64_decode(urldecode($img));
        $imagem = $this->path_temporario.$img;

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
        $img = base64_decode(urldecode($img));
        $imagem = $this->path_temporario.$img;

        $ok = (is_file($imagem) && !unlink($imagem)) ? '0' : '1';

        echo $ok;
    }
}