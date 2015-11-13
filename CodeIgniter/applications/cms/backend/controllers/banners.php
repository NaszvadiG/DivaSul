<?php
// Inclui a classe default_controller.php (não conseguimos por no autoload.)
include_once(APPPATH . 'controllers/default_controller.php');
class Banners extends Default_controller
{
    // Path para upload de arquivos
    private $path;

    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'cms_banners';
        $this->exibir_coluna_ordem = true;
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Banners_model');
        $this->Banners_model->set_table_name($this->table_name);

        // Define o path das imagens
        $this->path = SERVERPATH;

        // Site dir
        if ( strlen($this->session->userdata('site_dir')) )
        {
            $this->path .= $this->session->userdata('site_dir').'/';
        }

        // media/banners
        $this->path .= 'arquivos/banners/';
        if ( !is_dir($this->path) )
        {
            // Se não existe, cria-o
            mkdir($this->path);
        }
	
        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
               'titulo' => 'Ver categorias',
               'atributos_html' => array('class' => 'button',
                                         'title' => 'Alterar categorias',
                                         'style' => 'float:right;',
                                         'href' => base_url('banners/categorias_listar'))
            )
        );

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Banners';
        $this->module = '';
        $this->controller = 'banners';
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Aplica um WHERE na listagem
        $this->where = array('categoria_id IN (SELECT id FROM cms_banners_categorias WHERE site_id = '.$this->site_id.')');

        // Aplica um ORDER BY na listagem
        $this->ordens = array('categoria ASC, ordem ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
            ),
            array(
                'descricao' => 'Categoria', // Descrição (texto impresso na tela)
                'coluna' => 'categoria', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'categoria_id', // Coluna utilizada para ordenar/filtrar
                'sql' => '(SELECT titulo FROM cms_banners_categorias WHERE id = cms_banners.categoria_id)',
                'tamanho' => 85
            )
        );

        parent::listar();
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function categorias_listar($pagina_atual=1)
    {
        // Define a tabela principal deste módulo
        $this->set_table_name('cms_banners_categorias');
        $this->Banners_model->set_table_name($this->table_name);
        $this->exibir_coluna_ordem = false;

        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
               'titulo' => 'Voltar para banners',
               'atributos_html' => array('class' => 'button',
                                         'title' => 'Alterar banners',
                                         'style' => 'float:right;',
                                         'href' => base_url('banners/listar'))
            )
        );

        // Desabilita paginacao
        $this->desabilitar_paginacao = true;
        $this->desabilitar_buscar = true;
        $this->titulo = 'Banners - Categorias';
        $this->funcao_inserir = 'inserir_categoria';
        $this->funcao_editar = 'editar_categoria';
        $this->funcao_remover = 'remover_categoria';
        $this->funcao_ativar_inativar = 'ativar_inativar_categoria';
        $this->acoes[self::ACAO_EDITAR]['acao'] = $this->funcao_editar;
        $this->acoes[self::ACAO_REMOVER]['acao'] = $this->funcao_remover;

        // Retira coluna ativo
        unset($this->colunas_default[parent::COLUNA_ATIVO]);

        // Aplica um ORDER BY na listagem
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false
            ),
            array(
                'descricao' => 'Largura', // Descrição (texto impresso na tela)
                'tamanho' => '75',
                'coluna' => 'largura', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false
            ),
            array(
                'descricao' => 'Altura', // Descrição (texto impresso na tela)
                'tamanho' => '75',
                'coluna' => 'altura', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false
            ),
            array(
                'descricao' => 'Largura (miniatura)', // Descrição (texto impresso na tela)
                'tamanho' => '75',
                'coluna' => 'largura_miniatura', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false
            ),
            array(
                'descricao' => 'Altura (miniatura)', // Descrição (texto impresso na tela)
                'tamanho' => '75',
                'coluna' => 'altura_miniatura', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false
            )
        );
        $params = array(
            'columns' => array('*')
        );
        $this->registros = $this->Banners_model->listar($params);

        $dados = array();
        $dados['function'] = 'categorias_listar';
        parent::listar($dados);
    }

    function editar($id=null)
    {
        $img_banner = $_FILES['img_banner'];

        // Se for edição, obtém os dados "antigos"
        if ( strlen($id) > 0 )
        {
            $banner = $this->Banners_model->obter($id);

            // Catrgoria
            $categoria = $this->Banners_model->obter_categoria($banner['categoria_id']);
        }

        // Coluna ordem
        if ( strlen($banner['ordem']) == 0 )
        {
            // Obtém a maior ordem e soma 10
            $max_ordem = $this->Banners_model->max_ordem($banner['categoria_id']);
            $banner['ordem'] = $max_ordem+10;
        }

        // Se não for submit
        if ( !$this->input->post('submit') )
        {
            // Se não tem ID (inserção): obtém o próximo id da sequence
            $banner['id'] = is_null($banner['id']) ? $this->Banners_model->proximo_id() : $banner['id'];
        }

        // Se tem post, valida os dados
        if ( $this->input->post('submit') )
        {
            $dados = $this->input->post();
            $banner = $this->input->post('banner');

            // Catrgoria
            $categoria = $this->Banners_model->obter_categoria($banner['categoria_id']);

            // Quem postou o banner
            $banner['usuario_id'] = (int)$this->session->userdata('usuario_id');

            // Validação
            $this->form_validation->set_rules('banner[categoria_id]', 'Categoria', 'trim|required');
            $this->form_validation->set_rules('banner[ativo]', 'Ativo', 'trim|required');

            // Se não tem erros
            if ( $this->form_validation->run() )
            {
                $ok = false;
                if ( is_dir($this->path.$banner['categoria_id']) || mkdir($this->path.$banner['categoria_id']) )
                {
                    // Banner OLD
                    $banner_old = $this->Banners_model->obter($banner['id']);

                    // Se foi enviado um banner, armazena-o
                    if ( strlen($img_banner['tmp_name']) > 0 )
                    {
                        $imagem = array(
                            'name' => generate_url(current(explode('.',$img_banner['name']))).'.jpg',
                            'tmp_name' => $img_banner['tmp_name']
                        );

                        @unlink($this->path.$banner_old['img_banner']);

                        // Redimensiona a imagem
                        $ok = MY_Utils::redimensionar_imagem($imagem, $this->path.$banner['categoria_id'].'/'.$imagem['name'], $categoria['largura'], $categoria['altura']);
                        if ( strlen($categoria['largura_miniatura']) > 0 && strlen($categoria['altura_miniatura']) > 0  )
                        {
                            $ok = MY_Utils::redimensionar_imagem($imagem, $this->path.$banner['categoria_id'].'/thumb_'.$imagem['name'], $categoria['largura_miniatura'], $categoria['altura_miniatura'], false);
                            if ( $ok )
                            {
                                $banner['img_thumb'] = $banner['categoria_id'].'/thumb_'.$imagem['name'];
                            }
                        }

                        // Apaga a imagem temporária
                        @unlink($imagem['tmp_name']);

                        if ( $ok )
                        {
                            $banner['img_banner'] = $banner['categoria_id'].'/'.$imagem['name'];
                        }
                    }
                    // Caso contrário mantém o antigo
                    else
                    {
                        if ( strlen($banner_old['img_banner']) > 0 )
                        {
                            $banner['img_banner'] = $banner_old['img_banner'];
                        }

                        if ( ($banner_old['categoria_id'] != $banner['categoria_id']) && is_file($this->path.$banner_old['img_banner']) )
                        {
                            // Copia banner
                            copy($this->path.$banner_old['img_banner'], $this->path.$banner['categoria_id'].'/'.$banner['img_banner'].'.jpg');
                            unlink($this->path.$banner_old['img_banner']);
                            $banner['img_banner'] = $banner['categoria_id'].'/'.$banner['img_banner'].'.jpg';
                        }
                    }

                    if ( strlen($banner['img_banner']) > 0 )
                    {
                        list($img_width, $img_height) = getImageSize($this->path.$banner['img_banner']);
                        $voltar_para_cortar = false;
                        if ( $dados['imageW'] )
                        {
                            $targ_w = ($categoria['largura']>0?$categoria['largura']:$img_width);
                            $targ_h = ($categoria['altura']>0?$categoria['altura']:$img_height);
                            $jpeg_quality = 72;

                            $src = $this->path.$banner['img_banner'];
                            $img_r = imageCreateFromJpeg($src);
                            $dst_r = imageCreateTrueColor($targ_w, $targ_h);

                            $ok = imageCopyResampled($dst_r, $img_r, 0, 0, $dados['imageX'], $dados['imageY'], $targ_w, $targ_h, $dados['imageW'], $dados['imageH']);
                            $ok = imagejpeg($dst_r, $src, $jpeg_quality);
                        }
                        elseif ( $categoria['largura'] > 0 || $categoria['largura'] > 0 )
                        {
                            if ( $categoria['largura'] != $img_width || $categoria['altura'] != $img_height  )
                            {
                                $voltar_para_cortar = true;
                            }
                        }
                    }
                    else
                    {
                        $dados['erros'][] = 'Você precisa enviar ao menos uma imagem.';
                    }

                    // Salva o registro
                    $id = $this->Banners_model->salvar($banner);

                    if ( $id )
                    {
                        if ( $voltar_para_cortar )
                        {
                            redirect('banners/editar/'.$id);
                        }
                        else
                        {
                            redirect('banners');
                        }
                    }
                    else
                    {
                        $dados['erros'][] = 'Desculpe, mas não foi possível salvar o registro. Tente novamente.';
                    }
                }
                else
                {
                    $dados['erros'][] = 'Desculpe, mas não foi possível criar pasta para armazenar a imagem. Tente novamente.';
                }
            }
            else
            {
                $dados['erros'][] = 'Desculpe, mas não foi possível inserir o registo.';
                $dados['erros'][] = validation_errors();
            }
        }

        $dados['banner'] = $banner;
        $dados['categoria'] = $categoria;
        $dados['categorias'] = $this->Banners_model->listar_categorias($this->site_id);
        $dados['site_id'] = $this->site_id;
        $dados['path'] = $this->path;
        $dados['site_dir'] = $this->session->userdata('site_dir');

        $this->load->view('banners_editar', $dados);
    }

    function inserir_categoria()
    {
        $this->editar_categoria();
    }
    function editar_categoria($categoria_id=null)
    {
        // Carrega a model
        $this->set_table_name('cms_banners_categorias');
        $this->Banners_model->set_table_name($this->table_name);

        // Array de dados para a view
        $dados = array();
        // Obtém os dados
        if ( $this->input->post('submit') )
        {
            // se tem post, obtém do formulário
    		$dados = $this->input->post();
        }
        elseif ( !empty($categoria_id) )
        {
            // se não tem post e tem id, obtém da base de dados
    		$dados['categoria'] = $this->Banners_model->obter_categoria($categoria_id);
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Se existe procede, se não exibe erro!
            $dados = $this->input->post();

            // Validação
            $this->form_validation->set_rules('categoria[titulo]', 'Título', 'trim|required');
            $this->form_validation->set_rules('categoria[largura]', 'Largura', 'trim|required');
            $this->form_validation->set_rules('categoria[altura]', 'Altura', 'trim|required');
            if ( $this->form_validation->run() )
            {
                $dados['categoria']['largura'] = intval($dados['categoria']['largura']);
                $dados['categoria']['altura'] = intval($dados['categoria']['altura']);

                $dados['categoria']['site_id'] = $this->site_id;
                $ok = $this->Banners_model->salvar($dados['categoria']);
                if ( $ok )
                {
                    redirect('banners/categorias_listar');
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

        $this->load->view('banners_categorias_editar', $dados);
    }

    function remover($id)
    {
        $banner = $this->Banners_model->obter($id);
        
        // Apaga o arquivo
        $ok = true;
        if ( is_file($this->path.$banner['img_banner']) )
        {
            $ok = unlink($this->path.$banner['img_banner']);
        }

        if ( $ok )
        {
            return parent::remover($id);
        }
        else
        {
            $dados = array();
            $dados['banner'] = $banner;
            $dados['categorias'] = $this->Banners_model->listar_categorias($this->site_id);
            $dados['path'] = $this->path;
            $dados['erros'][] = 'Desculpe, mas não foi possível remover o registo.';
            $this->load->view('banners_editar', $dados);
        }
    }

    function remover_categoria($categoria_id)
    {
        // Carrega a model
        $this->table_name = 'cms_banners_categorias';
        $this->Banners_model->set_table_name($this->table_name);

        if ( $this->Banners_model->remover($categoria_id) )
        {
            redirect('banners/categorias_listar');
        }
        else
        {
            $this->editar_categoria($categoria_id);
        }
    }

    //AJAX
    function remover_imagem($id, $imagem)
    {
        $banner = $this->Banners_model->obter($id);
        if (is_file($this->path.$banner['img_thumb']) )
        {
            $ok = unlink($this->path.$banner['img_thumb']);
            if ( $ok )
            {
                $banner['img_thumb'] = '';
            }
        }
        if (is_file($this->path.$banner['img_banner']) )
        {
            $ok = unlink($this->path.$banner['img_banner']);
            if ( $ok )
            {
                $banner['img_banner'] = '';
            }
        }
        $ok = $this->Banners_model->salvar($banner);
        echo $ok ? '1' : '0';
    }
}
?>
