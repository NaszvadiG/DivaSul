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
        $this->exibir_coluna_ordem = false;
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Produtos_model');
        $this->load->model('Cidades_model');

        // Define o path das imagens
        $this->path = 'arquivos/produtos/';
        if ( !is_dir(SERVERPATH.$this->path) && !mkdir(SERVERPATH.$this->path) )
        {
            $dados['error'] = 'Falha ao criar diretório (produtos).';
        }

        // Path temporário
        $this->path_temporario = '/tmp/produtos_'.$this->usuario_id.'/';
        if ( !is_dir($this->path_temporario) && !mkdir($this->path_temporario) )
        {
            $dados['error'] = 'Falha ao criar diretório temporário (produtos).';
        }
    
        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
               'titulo' => '<i class="fa fa-search"></i> Categorias',
               'atributos_html' => array(
                   'class' => 'btn btn-primary',
                   'title' => 'Listar tipos de produtos',
                   'style' => 'float:right;margin-right:5px;',
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
        $this->registros_por_pagina = 25;

        // Aplica um ORDER BY na listagem
        $this->ordens = array('id DESC');

        // Define botoes extra na coluna Ações
        $this->acoes[-2] = array(
            'descricao' => 'Ver estoque', // Descrição
            'acao' => 'ver_estoque', // Função do controller que será chamada (ação)
            'icone' => 'arquivos/css/icons/lupa.png' // Imagem do botão
        );
        $this->acoes[-1] = array(
            'descricao' => 'Alterar estoque', // Descrição
            'acao' => 'alterar_estoque', // Função do controller que será chamada (ação)
            'icone' => 'arquivos/css/icons/estoque.png' // Imagem do botão
        );

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Nome', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true,
                'linkar_para_edicao' => true,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Tipo', // Descrição (texto impresso na tela)
                'coluna' => 'tipo_id', // Coluna no array de dados ($this->registros)
                'coluna_sql' => '(SELECT titulo FROM site_produtos_tipos WHERE id = site_produtos.tipo_id)',
                'coluna_filtravel' => true
            ),
            array(
                'descricao' => 'Categoria', // Descrição (texto impresso na tela)
                'coluna' => 'categoria_id', // Coluna no array de dados ($this->registros)
                'coluna_sql' => '(SELECT titulo FROM site_produtos_categorias WHERE id = site_produtos.categoria_id)',
                'coluna_filtravel' => true,
                'tamanho' => 45
            ),
            array(
                'descricao' => 'Marca', // Descrição (texto impresso na tela)
                'coluna' => 'marca', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Estoque', // Descrição (texto impresso na tela)
                'coluna' => 'estoque', // Coluna no array de dados ($this->registros)
                'coluna_sql' => '(CASE WHEN tipo_id = 2 THEN (SELECT MIN(COMP.estoque)
                                                                FROM site_produtos_kits KIT
                                                          INNER JOIN site_produtos COMP
                                                                  ON COMP.id = KIT.componente_id
                                                               WHERE KIT.produto_id = site_produtos.id)
                                                        ELSE estoque END)'
            ),
            array(
                'descricao' => 'Valor de Venda', // Descrição (texto impresso na tela)
                'coluna' => 'valor_venda', // Coluna no array de dados ($this->registros)
                'coluna_sql' => "CONCAT('R$ ', REPLACE(valor_venda,'.',','))"
            )
        );

        /*
        $this->acoes[1] = array(
            'descricao' => 'Visualizar', // Descrição
            'acao' => 'visualizar', // Função do controller que será chamada (ação)
            'icone' => 'arquivos/css/icons/search.png' // Imagem do botão
        );
        */

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
                'titulo' => '<i class="fa fa-search"></i> Voltar para produtos',
                'atributos_html' => array(
                   'class' => 'btn btn-primary',
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
                'tipo' => 'string',
            ),
            /*
            array(
                'descricao' => 'Categoria Pai', // Descrição (texto impresso na tela)
                'coluna' => 'pai', // Coluna no array de dados ($this->registros)
                'coluna_sql' => "COALESCE((SELECT titulo FROM site_produtos_categorias WHERE id = site_produtos_categorias.parent_id),'-')",
                'coluna_filtravel' => true,
                'tamanho' => 45
            )
            */
        );

        $dados = array();
        $dados['function'] = 'listar_categorias';

        parent::listar($dados);
    }

    function editar($id=null)
    {
        // Array de dados para a view
        $dados = array();

        // Carrega a model
        $this->load->model('Produtos_model');

        // Obtém os dados
        if ( $this->input->post('submit') )
        {
            // se tem post, obtém do formulário
            $dados = $this->input->post();
        }
        elseif ( (int)$id > 0 )
        {
            // se tem id, obtém da base
            $dados['registro'] = $produto = $this->Produtos_model->obter($id);
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Validação
            $this->form_validation->set_rules('registro[categoria_id]', 'Categoria', 'trim|required');
            $this->form_validation->set_rules('registro[tipo_id]', 'Tipo', 'trim|required');
            $this->form_validation->set_rules('registro[titulo]', 'Título', 'trim|required');
            $this->form_validation->set_rules('registro[ativo]', 'Ativo', 'trim|required');
            if ( $this->form_validation->run() )
            {
                $dados['registro']['link'] = str_replace('-', '_', MY_Utils::removeSpecialChars(strtolower(utf8_decode($dados['registro']['titulo']))));
                $dados['registro']['link'] = preg_replace('/_{2,}/','_',$dados['registro']['link']);

                // Converte tudo pra maiusculo
                foreach ( $dados['registro'] as $k => $valor )
                {
                    $dados['registro'][$k] = strtoupper($valor);
                }

                // Salva o produto
                $dados['registro']['id'] = $ok = $this->Produtos_model->salvar($dados['registro']);

                // Salva os componentes do kit
                if ( $dados['registro']['tipo_id'] == '2' )
                {
                    // Produtos componentes do kit
                    $componentes = array();
                    if ( $dados['registro']['tipo_id'] == '2' )
                    {
                        foreach ( $dados['produto_id'] as $k => $valor )
                        {
                            $componentes[] = array(
                                'produto_id' => $dados['registro']['id'],
                                'componente_id' => $valor,
                                'quantidade' => $dados['produto_quantidade'][$k]
                            );
                        }
                    }
                    $this->Default_model->set_table_name('site_produtos_kits');
                    $this->Default_model->remover($dados['registro']['id'], 'produto_id');
                    foreach ( $componentes as $componente )
                    {
                        $this->Default_model->salvar($componente);
                    }
                }
                $dados['registro']['id'] = $ok;
                $path_destino_imagens = SERVERPATH.$this->path.$dados['registro']['id'].'/';
                // Se existe procede, se não exibe erro!
                if ( is_dir(SERVERPATH.$this->path.$dados['registro']['id']) || mkdir(SERVERPATH.$this->path.$dados['registro']['id']) )
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
                                if ( strlen($dados['registro']['foto_capa']) == 0 )
                                {
                                    $dados['registro']['foto_capa'] = $imagem_thumb['name'];
                                    $ok = $this->Produtos_model->salvar($dados['registro']);
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

        // Definição dos campos
        // Codigo
        $campos = array();
        $campo = array();
        $campo['id'] = 'id';
        $campo['name'] = 'registro[id]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'Código';
        $campo['placeholder'] = 'Código do produto';
        $campo['value'] = $dados['registro']['id'];
        if ( (int)$dados['registro']['id'] == 0 )
        {
            $campo['attrs'] = 'readonly';
        }
        $campos[] = $campo;
        // Tipo
        $campo = array();
        $tipos = array();
        $this->Default_model->set_table_name('site_produtos_tipos');
        $arr_aux = $this->Default_model->listar();
        foreach ( $arr_aux as $tipo )
        {
            $tipos[$tipo['id']] = $tipo['titulo'];
        }
        $campo['id'] = 'tipo_id';
        $campo['name'] = 'registro[tipo_id]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Tipo';
        $campo['placeholder'] = 'Tipo de produto';
        $campo['value'] = $dados['registro']['tipo_id'];
        $campo['options'] = $tipos;
        $campo['required'] = true;
        //$campo['attrs'] = 'readonly';
        //$campo['hidden'] = 'true';
        $campos[] = $campo;
        // Nome do produto
        $campo = array();
        $campo['id'] = 'titulo';
        $campo['name'] = 'registro[titulo]';
        $campo['tamanho'] = 5;
        $campo['type'] = 'text';
        $campo['label'] = 'Nome';
        $campo['placeholder'] = 'Nome do produto';
        $campo['value'] = $dados['registro']['titulo'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Categoria
        $campo = array();
        $categorias = array();
        $arr_aux = $this->Produtos_model->listar_categorias();
        foreach ( $arr_aux as $categoria )
        {
            $categorias[$categoria['id']] = $categoria['titulo'];
        }
        $campo['id'] = 'categoria_id';
        $campo['name'] = 'registro[categoria_id]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Categoria';
        $campo['placeholder'] = 'Categoria do produto';
        $campo['value'] = $dados['registro']['categoria_id'];
        $campo['options'] = $categorias;
        $campo['required'] = true;
        $campos[] = $campo;
        // Marca do produto
        $campo = array();
        $campo['id'] = 'marca';
        $campo['name'] = 'registro[marca]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Marca';
        $campo['placeholder'] = 'Marca do produto';
        $campo['value'] = $dados['registro']['titulo'];
        $campo['autocomplete'] = 'autocomplete_marca';
        $campos[] = $campo;
        // Descrição do produto
        $campo = array();
        $campo['id'] = 'descricao';
        $campo['name'] = 'registro[descricao]';
        $campo['tamanho'] = 9;
        $campo['type'] = 'text';
        $campo['label'] = 'Descrição';
        $campo['placeholder'] = 'Descrição do produto';
        $campo['value'] = $dados['registro']['descricao'];
        $campos[] = $campo;
        // Valor do produto
        $campo = array();
        $campo['id'] = 'valor_compra';
        $campo['name'] = 'registro[valor_compra]';
        $campo['tamanho'] = 4;
        $campo['type'] = 'number';
        $campo['label'] = 'Valor de compra';
        $campo['placeholder'] = 'Valor de compra do produto';
        $campo['value'] = $dados['registro']['valor_compra'];
        $campo['attrs'] = 'pattern="^\d+(\.|\,)\d{2}$" step="any"';
        $campo['required'] = true;
        $campo['pre'] = '<span class="input-group-addon">R$</span>';
        $campos[] = $campo;
        // Promoção
        $campo = array();
        $campo['id'] = 'promocao';
        $campo['name'] = 'registro[promocao]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Promoção';
        $campo['placeholder'] = 'Produto em promoção';
        $campo['value'] = $dados['registro']['promocao'];
        $campo['options'] = array('0'=>'Não','1'=>'Sim');
        $campos[] = $campo;
        // Vigencia da Promoção
        $campo = array();
        $campo['id'] = 'promocao_inicio';
        $campo['name'] = 'registro[promocao_inicio]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'date';
        $campo['label'] = 'Promoção de';
        $campo['placeholder'] = 'Produto em promoção a partir de';
        $campo['value'] = $dados['registro']['promocao_inicio'];
        if ( (int)$dados['registro']['promocao'] != 1 )
        {
            $campo['attrs'] = 'readonly display';
        }
        $campos[] = $campo;
        // Vigencia da Promoção
        $campo = array();
        $campo['id'] = 'promocao_fim';
        $campo['name'] = 'registro[promocao_fim]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'date';
        $campo['label'] = 'Promoção até';
        $campo['placeholder'] = 'Produto em promoção até';
        $campo['value'] = $dados['registro']['promocao_fim'];
        if ( (int)$dados['registro']['promocao'] != 1 )
        {
            $campo['attrs'] = 'readonly disabled';
        }
        $campos[] = $campo;
        // Valor de venda minimo do produto
        $campo = array();
        $campo['id'] = 'valor_venda_minimo';
        $campo['name'] = 'registro[valor_venda_minimo]';
        $campo['tamanho'] = 4;
        $campo['type'] = 'number';
        $campo['label'] = 'Valor mínimo p/ venda';
        $campo['placeholder'] = 'Valor de venda mínimo do produto';
        $campo['value'] = $dados['registro']['valor_venda_minimo'];
        $campo['attrs'] = 'pattern="^\d+(\.|\,)\d{2}$" step="any"';
        $campo['required'] = true;
        $campo['pre'] = '<span class="input-group-addon">R$</span>';
        $campos[] = $campo;
        // Valor de venda do produto
        $campo = array();
        $campo['id'] = 'valor_venda';
        $campo['name'] = 'registro[valor_venda]';
        $campo['tamanho'] = 4;
        $campo['type'] = 'number';
        $campo['label'] = 'Valor venda atual';
        $campo['placeholder'] = 'Valor de venda do produto';
        $campo['value'] = $dados['registro']['valor_venda'];
        $campo['attrs'] = 'pattern="^\d+(\.|\,)\d{2}$" step="any"';
        $campo['required'] = true;
        $campo['pre'] = '<span class="input-group-addon">R$</span>';
        $campos[] = $campo;
        // Ativo
        $campo = array();
        $campo['id'] = 'ativo';
        $campo['name'] = 'registro[ativo]';
        $campo['tamanho'] = 4;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Ativo';
        $campo['placeholder'] = 'Situação do produto';
        $campo['value'] = $dados['registro']['ativo'];
        $campo['options'] = array('1'=>'Sim','0'=>'Não');
        $campo['required'] = true;
        $campos[] = $campo;
        // Fotos do produto
        /*
        $campo = array();
        $campo['id'] = 'imagens';
        $campo['tamanho'] = 12;
        $campo['type'] = 'multi_upload';
        $campo['foto_capa'] = true;
        $campo['value'] = $campo['foto_capa'];
        $campos[] = $campo;
        */
        // Produtos
        $campo = array();
        $campo['id'] = 'produtos';
        $campo['name'] = 'registro[produtos]';
        $campo['tamanho'] = 12;
        $campo['type'] = 'subdetail';
        $campo['label'] = 'Produtos';
        $campo['colunas'] = array(
            'id' => array(
                'titulo' => 'Código',
                'attr' => array(
                    'data-identifier="true"',
                    'data-type="numeric"',
                    'data-order="desc"',
                    //'data-width="10%"'
                    'width="10%"'
                )
            ),
            'titulo' => array(
                'titulo' => 'Nome',
                'attr' => array(
                    'width="50%"'
                )
            ),
            'valor_compra' => array(
                'titulo' => 'Valor compra',
                'attr' => array(
                    'data-type="numeric"',
                    'width="10%"'
                )
            ),
            'valor_venda_minimo' => array(
                'titulo' => 'Valor venda mínimo',
                'attr' => array(
                    'data-type="numeric"',
                    'width="10%"'
                )
            ),
            'valor_venda' => array(
                'titulo' => 'Valor venda atual',
                'attr' => array(
                    'data-type="numeric"',
                    'width="10%"'
                )
            ),
            'quantidade' => array(
                'titulo' => 'Quantidade',
                'attr' => array(
                    'data-type="numeric"',
                    'width="10%"'
                )
            ),
            'acoes' => array(
                'titulo' => 'Ações',
                'attr' => array(
                    'data-formatter="commands"',
                    'data-sortable="false"',
                    'width="10%"'
                )
            )
        );
        $campo['colunas_editaveis'] = array('quantidade');
        $campo['json'] = 'ajax_listar_produtos';
        $componentes = array();
        if ( $dados['registro']['id'] )
        {
            $componentes = $this->Produtos_model->kit_obter_componentes($dados['registro']['id']);
        }
        $cols = array();
        if ( $componentes && is_array($componentes) && count($componentes) > 0 )
        {
            foreach ( $componentes as $componente )
            {
                $prod = $this->Produtos_model->obter($componente['componente_id']);
                $coluna = array();
                $coluna[] = '<td>'.$prod['id'].'<input type="hidden" name="produto_id[]" value="'.$prod['id'].'"/></td>';
                $coluna[] = '<td>'.$prod['titulo'].'</td>';
                $coluna[] = '<td>R$ '.str_replace('.', ',', $prod['valor_compra']).'</td>';
                $coluna[] = '<td>R$ '.str_replace('.', ',', $prod['valor_venda_minimo']).'</td>';
                $coluna[] = '<td>R$ '.str_replace('.', ',', $prod['valor_venda']).'</td>';
                $coluna[] = '<td><input type="number" name="produto_quantidade[]" pattern="^\d+(\.|\,)\d{2}$" step="any" value="'.$componente['quantidade'].'" class="input_quantidade"/></td>';
                $coluna[] = '<td><button class="btn btn-danger bt_remover" title="Remover"><i class="fa fa-trash"></i></button></td>';
                $cols[] = $coluna;
            }
        }
        $campo['value'] = $cols;
        $campo['required'] = true;
        if ( $dados['registro']['tipo_id'] != '2' )
        {
            $campo['hidden'] = true;
        }
        $campos[] = $campo;

        // Campos do formulário
        $dados['campos'] = $campos;

        // Adiciona JS
        $dados['custom_js'] = <<<HTML
$('body').on('change', '#tipo_id', function()
{
  if ( $(this).val() == 1 )
  {
    $('#campo_produtos').hide(100);
  }
  else
  {
    $('#campo_produtos').show(100);
  }
});

$('body').on('change', '#promocao', function()
{
  if ( $(this).val() == 1 )
  {
    // habilita campo de
    $('#promocao_inicio').removeAttr('readonly');
    $('#promocao_inicio').removeAttr('disabled');
    $($('#promocao_inicio').parent().children()[0]).append('<span class="required">*</span>')
    // habilita campo ate
    $('#promocao_fim').removeAttr('readonly');
    $('#promocao_fim').removeAttr('disabled');
    $($('#promocao_fim').parent().children()[0]).append('<span class="required">*</span>')
  }
  else
  {
    // desabilita campo de
    $('#promocao_inicio').attr('readonly', true);
    $('#promocao_inicio').attr('disabled', true);
    $($($('#promocao_inicio').parent().children()[0]).children()[0]).remove()
    // desabilita campo ate
    $('#promocao_fim').attr('readonly', true);
    $('#promocao_fim').attr('disabled', true);
    $($($('#promocao_fim').parent().children()[0]).children()[0]).remove()
  }
});

$('#bt_add_produto').click(function(event)
{
    event.preventDefault();

    $('body').append('<div id="add_produto_subdetail" class="modal">\
    <div class="modal-dialog">\
        <div class="modal-content">\
            <div class="modal-header">\
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">\
                <span aria-hidden="true">×</span></button>\
                <h4 class="modal-title">Adicionar componente do kit</h4>\
            </div>\
            <div class="modal-body">\
                <div class="input-group">\
<span class="input-group-addon"><i class="fa fa-search"></i></span>\
<input type="text" id="busca_produto" class="form-control" name="busca[produto]" placeholder="Nome do produto" title="Pesquise aqui pelo nome do produto" value="" autofocus>\
  </div>\
                <br>\
                <table class="zebra">\
                    <thead>\
                        <tr>\
                            <th style="width:10px">Código</th>\
                            <th style="width:50%">Nome</th>\
                            <th style="width:10%">Valor compra</th>\
                            <th style="width:10%">Valor venda mínimo</th>\
                            <th style="width:10%">Valor venda atual</th>\
                            <th style="width:10%">Açoes</th>\
                        </tr>\
                    </thead>\
                    <tbody id="subdetail_produtos_adicionar">\
                    </tbody>\
                </table>\
            </div>\
            <div class="modal-footer">\
                <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Fechar</button>\
            </div>\
        </div>\
    </div>\
</div>');
    $('#add_produto_subdetail.modal').show(100);

    var ajax_interval = 0;
    $('body').on('keyup', '#busca_produto', function()
    {
        var busca = $(this).val();
        //loading
        $('#subdetail_produtos_adicionar').html('<tr><td colspan="6"><div style="width:100%;padding:25px 0px;text-align:center"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i></div></td></tr>');
        if ( busca.length >= 3 )
        {
            var ids = new Array();

            // começa a contar o tempo
            clearInterval(ajax_interval);

            // 500ms após o usuário parar de digitar a função é chamada  
            ajax_interval = window.setTimeout(function()
            {
                ids.push('%id%');
                $('#subdetail_produtos td input[type="hidden"]').each(function(index, element)
                {
                    ids.push($(element).val());
                });

                $.ajax(
                {
                    type: 'POST',
                    url: '%url_ajax%',
                    data: {busca:busca,ids:ids},
                    success: function(data)
                    {
                        $('#subdetail_produtos_adicionar').html(data);
                    }
                });
            }, 500);
        }
        else
        {
            $('#subdetail_produtos_adicionar').html('<tr><td colspan="6">Digite pelo menos 3 caracteres.</td></tr>');
        }
    });

    $('#busca_produto').focus();
});

var primeiraVez = true;
$('body').on('click', '.bt_add', function()
{
    // Obtém os valores
    var _valor_compra = +$('#valor_compra').val();
    var _valor_venda_minimo = +$('#valor_venda_minimo').val();
    var _valor_venda = +$('#valor_venda').val();
    if ( primeiraVez )
    {
        primeiraVez = false;

        // Zera
        _valor_compra = 0;
        _valor_venda_minimo = 0;
        _valor_venda = 0;
    }

    var id = $($(this).parent().parent().children()[0]).html().trim();
    var titulo = $($(this).parent().parent().children()[1]).html().trim();
    var valor_compra = $($(this).parent().parent().children()[2]).html().trim();
    var valor_venda_minimo = $($(this).parent().parent().children()[3]).html().trim();
    var valor_venda = $($(this).parent().parent().children()[4]).html().trim();

    $('#subdetail_produtos').append('<tr>\
        <td>'+id+'<input type="hidden" name="produto_id[]" value="'+id+'"/></td>\
        <td>'+titulo+'</td>\
        <td>'+valor_compra+'</td>\
        <td>'+valor_venda_minimo+'</td>\
        <td>'+valor_venda+'</td>\
        <td><input type="number" name="produto_quantidade[]" pattern="^\d+(\.|\,)\d{2}$" step="any" class="input_quantidade" value="1"/>\
</td>\
        <td><button class="btn btn-danger bt_remover" title="Remover"><i class="fa fa-trash"></i></button></td>\
    </tr>');

    $(this).parent().parent().remove();
    //$('#add_produto_subdetail .modal-footer button').click();

    // Atualiza os valores
    $('#valor_compra').val((_valor_compra + +valor_compra.replace('R$ ', '').replace(',', '.')).toFixed(2));
    $('#valor_venda_minimo').val((_valor_venda_minimo + +valor_venda_minimo.replace('R$ ', '').replace(',', '.')).toFixed(2));
    $('#valor_venda').val((_valor_venda + +valor_venda.replace('R$ ', '').replace(',', '.')).toFixed(2));
});

$('body').on('click', '.bt_remover', function(event)
{
    event.preventDefault();
    $(this).parent().parent().remove();

    // Dados do produto
    var valor_compra = $($(this).parent().parent().children()[2]).html().trim();
    var valor_venda_minimo = $($(this).parent().parent().children()[3]).html().trim();
    var valor_venda = $($(this).parent().parent().children()[4]).html().trim();

    // Valores atuais
    var _valor_compra = $('#valor_compra').val();
    var _valor_venda_minimo = $('#valor_venda_minimo').val();
    var _valor_venda = $('#valor_venda').val();

    // Atualiza os valores
    $('#valor_compra').val((_valor_compra - +valor_compra.replace('R$ ', '').replace(',', '.')).toFixed(2));
    $('#valor_venda_minimo').val((_valor_venda_minimo - +valor_venda_minimo.replace('R$ ', '').replace(',', '.')).toFixed(2));
    $('#valor_venda').val((_valor_venda - +valor_venda.replace('R$ ', '').replace(',', '.')).toFixed(2));
});

$('body').on('keyup', '.input_quantidade', function(event)
{
    // Dados do produto
    var valor_compra = $($(this).parent().parent().children()[2]).html().trim();
    var valor_venda_minimo = $($(this).parent().parent().children()[3]).html().trim();
    var valor_venda = $($(this).parent().parent().children()[4]).html().trim();
console.log($(this));

    // Valores atuais
    var _valor_compra = $('#valor_compra').val();
    var _valor_venda_minimo = $('#valor_venda_minimo').val();
    var _valor_venda = $('#valor_venda').val();

    // Atualiza os valores
    $('#valor_compra').val((_valor_compra + +valor_compra.replace('R$ ', '').replace(',', '.')).toFixed(2));
    $('#valor_venda_minimo').val((_valor_venda_minimo + +valor_venda_minimo.replace('R$ ', '').replace(',', '.')).toFixed(2));
    $('#valor_venda').val((_valor_venda + +valor_venda.replace('R$ ', '').replace(',', '.')).toFixed(2));
});

$('body').on('change', '.input_quantidade', function(event)
{
    // Dados do produto
    var valor_compra = $($(this).parent().parent().children()[2]).html().trim();
    var valor_venda_minimo = $($(this).parent().parent().children()[3]).html().trim();
    var valor_venda = $($(this).parent().parent().children()[4]).html().trim();
console.log($(this));

    // Valores atuais
    var _valor_compra = $('#valor_compra').val();
    var _valor_venda_minimo = $('#valor_venda_minimo').val();
    var _valor_venda = $('#valor_venda').val();

    // Atualiza os valores
    $('#valor_compra').val((_valor_compra + +valor_compra.replace('R$ ', '').replace(',', '.')).toFixed(2));
    $('#valor_venda_minimo').val((_valor_venda_minimo + +valor_venda_minimo.replace('R$ ', '').replace(',', '.')).toFixed(2));
    $('#valor_venda').val((_valor_venda + +valor_venda.replace('R$ ', '').replace(',', '.')).toFixed(2));
});

$('#titulo').focus();
HTML;
        $dados['custom_js'] = str_replace('%id%', $id, $dados['custom_js']);
        $dados['custom_js'] = str_replace('%url_ajax%', base_url('site/produtos/ajax_listar_produtos'), $dados['custom_js']);
        $dados['custom_js'] = str_replace('%url_ajax_precos%', base_url('site/produtos/ajax_precos'), $dados['custom_js']);

        parent::load_view($dados);
    }

    function inserir_categoria()
    {
        $this->editar_categoria();
    }
    function editar_categoria($id=null)
    {
        // Array de dados para a view
        $dados = array();

        // Carrega a model
        $this->table_name = 'site_produtos_categorias';
        $this->Produtos_model->set_table_name($this->table_name);
        // Obtém os dados
        if ( (int)$id > 0 )
        {
            // se não tem post e tem id, obtém da base de dados
            $dados['registro'] = $registro = $this->Produtos_model->obter($id);
        }

        // Obtém os dados
        if ( $this->input->post('submit') )
        {
            // se tem post, obtém do formulário
            $dados = $this->input->post();
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Validação
            $this->form_validation->set_rules('registro[id]', 'Código', 'trim|required');
            $this->form_validation->set_rules('registro[titulo]', 'Nome', 'trim|required');
            if ( $this->form_validation->run() )
            {
                // Converte tudo pra maiusculo
                foreach ( $dados['registro'] as $k => $valor )
                {
                    $dados['registro'][$k] = strtoupper($valor);
                }

                $id = $this->Produtos_model->salvar($dados['categoria']);
                if ( $id )
                {
                    redirect('site/produtos/listar_categorias');
                }
                else
                {
                    $dados['erro'] = 'Lamento, mas não foi possível salvar.';
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

        // Definição dos campos
        // Codigo
        $campos = array();
        $campo = array();
        $campo['id'] = 'id';
        $campo['name'] = 'registro[id]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'Código';
        $campo['placeholder'] = 'Código da categoria';
        $campo['value'] = $registro['id'];
        $campo['attrs'] = 'readonly';
        $campos[] = $campo;
        // Nome do produto
        $campo = array();
        $campo['id'] = 'titulo';
        $campo['tamanho'] = 10;
        $campo['type'] = 'text';
        $campo['label'] = 'Título';
        $campo['placeholder'] = 'Nome da categoria';
        $campo['value'] = $registro['titulo'];
        $campos[] = $campo;

        $dados['campos'] = $campos;

        $this->titulo = 'Categoria do produto';
        $this->funcao_editar = 'editar_categoria';
        $this->funcao_listar = 'listar_categorias';

        parent::load_view($dados);
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

    /**
     * Ajax para pegar autocomplete das marcas
     */
    function autocomplete_marca($search='')
    {
        $marcas = $this->Produtos_model->buscar_marca(trim($search));
        foreach ( $marcas as $k => $marca )
        {
            $marcas[$k] = $marca['marca'];
        }
        $marcas = implode('|#|', $marcas);

        echo $marcas;
    }

    function alterar_estoque($id=null)
    {
        // Array de dados para a view
        $dados = array();

        // Carrega a model
        $this->load->model('Produtos_model');
        if ( (int)$id > 0 )
        {
            $produto = $this->Produtos_model->obter($id);
        }
        else
        {
            redirect('site/produtos');
        }

        // Obtém os dados
        if ( $this->input->post('submit') )
        {
            // se tem post, obtém do formulário
            $dados = $this->input->post();
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Validação
            $this->form_validation->set_rules('registro[produto_id]', 'Produto', 'trim|required');
            $this->form_validation->set_rules('registro[quantidade]', 'Quantidade', 'trim|required');
            $this->form_validation->set_rules('registro[obs]', 'Observação', 'trim|required');
            if ( $this->form_validation->run() )
            {
                if ( (int)$dados['registro']['quantidade'] <> 0 )
                {
                    // Converte tudo pra maiusculo
                    foreach ( $dados['registro'] as $k => $valor )
                    {
                        $dados['registro'][$k] = strtoupper($valor);
                    }

                    $ok = $this->Produtos_model->alterar_estoque($dados['registro']['produto_id'], $dados['registro']['quantidade'], $dados['registro']['obs']);

                    if ( $ok )
                    {
                        redirect('site/produtos');
                    }
                    else
                    {
                        $dados['erro'] = 'Desculpe, mas não foi possível alterar o estoque.';
                    }
                }
                else
                {
                    $dados['erro'] = 'O campo Quantidade não pode ser zero.';
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

        // Definição dos campos
        // Codigo
        $campos = array();
        $campo = array();
        $campo['id'] = 'produto_id';
        $campo['name'] = 'registro[produto_id]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'Código';
        $campo['placeholder'] = 'Código do produto';
        $campo['value'] = $produto['id'];
        $campo['attrs'] = 'readonly';
        $campos[] = $campo;
        // Nome do produto
        $campo = array();
        $campo['id'] = 'produto';
        $campo['tamanho'] = 10;
        $campo['type'] = 'text';
        $campo['label'] = 'Produto';
        $campo['placeholder'] = 'Nome do produto';
        $campo['value'] = $produto['titulo'];
        $campo['attrs'] = 'readonly disabled';
        $campos[] = $campo;
        // Quantidade
        $campo = array();
        $campo['id'] = 'quantidade';
        $campo['name'] = 'registro[quantidade]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'number';
        $campo['label'] = 'Quantidade';
        $campo['placeholder'] = 'Qtd';
        $campo['value'] = 0;
        $campo['attrs'] = 'pattern="^\d+(\.|\,)\d{2}$" step="any"';
        $campo['required'] = true;
        $campo['pre'] = '<span class="input-group-addon"><i class="fa fa-th-large"></i></span>';
        $campos[] = $campo;
        // Descrição da alteração de estoque
        $campo = array();
        $campo['id'] = 'obs';
        $campo['name'] = 'registro[obs]';
        $campo['tamanho'] = 10;
        $campo['type'] = 'text';
        $campo['label'] = 'Observação';
        $campo['placeholder'] = 'Observação da alteração de estoque';
        $campo['required'] = true;
        $campos[] = $campo;

        $dados['campos'] = $campos;
        $dados['registro']['id'] = $produto['id'];

        $this->titulo = 'Alteração de estoque do produto';
        $this->funcao_editar = 'alterar_estoque';

        parent::load_view($dados);
    }

    function ver_estoque($id=null)
    {
        // Array de dados para a view
        $dados = array();

        // Carrega a model
        $this->load->model('Produtos_model');

        // Define a tabela principal deste módulo
        $this->table_name = 'site_produtos_estoque';
        $this->Default_model->set_table_name($this->table_name);

        // Se tem código
        if ( (int)$id > 0 )
        {
            // Obtém os dados do produto
            $produto = $this->Produtos_model->obter($id);
            if ( !$produto['id'] )
            {
                // Volta para a listagem
                redirect('site/produtos');
            }
        }
        else
        {
            // Volta para a listagem
            redirect('site/produtos');
        }

        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
                'titulo' => '<i class="fa fa-search"></i> Voltar para produtos',
                'atributos_html' => array(
                   'class' => 'btn btn-primary',
                   'title' => 'Voltar para produtos',
                   'style' => 'float:right;',
                   'href' => base_url('site/produtos/listar')
               )
            )
        );

        // Desabilita coluna ordem, busca, paginacao, ordenação, bt inserir, ...
        $this->exibir_coluna_ordem = false;
        $this->desabilitar_buscar = true;
        $this->desabilitar_paginacao = true;
        $this->desabilitar_ordenacao = true;
        $this->desabilitar_inserir = true;
        $this->titulo = 'Ver estoque';
        $this->colunas_default = array();

        // Aplica um WHERE na listagem
        if ( $produto['tipo_id'] == '2' )//kit
        {
            $this->where = array('produto_id IN (SELECT componente_id
                                                   FROM site_produtos_kits
                                                  WHERE produto_id = '.$id.')');
        }
        else
        {
            $this->where = array('produto_id = '.$id);
        }

        // Aplica um ORDER BY na listagem
        $this->ordens = array('data_hora DESC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Código', // Descrição (texto impresso na tela)
                'coluna' => 'produto_id' // Coluna no array de dados ($this->registros)
            ),
            array(
                'descricao' => 'Produto', // Descrição (texto impresso na tela)
                'coluna' => 'produto', // Coluna no array de dados ($this->registros)
                'coluna_sql' => '(SELECT titulo FROM site_produtos WHERE id = site_produtos_estoque.produto_id)'
            ),
            array(
                'descricao' => 'Quantidade', // Descrição (texto impresso na tela)
                'coluna' => 'quantidade' // Coluna no array de dados ($this->registros)
            ),
            array(
                'descricao' => 'Observação', // Descrição (texto impresso na tela)
                'coluna' => 'obs' // Coluna no array de dados ($this->registros)
            ),
            array(
                'descricao' => 'Data/hora', // Descrição (texto impresso na tela)
                'coluna' => "DATE_FORMAT(data_hora, '%d/%m/%Y %H:%i:%s')" // Coluna no array de dados ($this->registros)
            )
        );

        $dados = array();
        $dados['function'] = 'ver_estoque';
        $dados['pagina_atual'] = $dados['pagina_atual'] ? $dados['pagina_atual'] : $_POST['pagina_atual'];// $dados ou $_POST
        $dados['pagina_atual'] = $dados['pagina_atual'] ? $dados['pagina_atual'] : $this->uri->segment(5);// $dados ou URI
        $dados['pagina_atual'] = $dados['pagina_atual'] ? $dados['pagina_atual'] : 1; // $dados ou 1
        parent::listar($dados);

        $total_estoque = $this->Produtos_model->obter_estoque($id);

        $html .= '<div class="row text-center">';
        $html .= 'Estoque atual: <b>'.$total_estoque.'</b>.';
        $html .= '</div>';
        $dados = array();
        $dados['html'] = $html;

        $this->load->view('html', $dados);
    }

    function ajax_listar_produtos()
    {
        $busca = $this->input->post('busca');
        $ids = $this->input->post('ids');

        $html = '';
        $produtos = $this->Produtos_model->buscar($busca);
        if ( is_array($produtos) && count($produtos) > 0 )
        {
            foreach ( $produtos as $produto )
            {
                // Se não for kit
                if ( $produto['tipo_id'] != '2' )
                {
                    // Se não for ele mesmo nem seus componentes
                    if ( !in_array($produto['id'], $ids) )
                    {
                        $html .= '<tr>';
                        $html .= '<td>'.$produto['id'].'</td>';
                        $html .= '<td>'.$produto['titulo'].'</td>';
                        $html .= '<td>R$ '.str_replace('.', ',', $produto['valor_compra']).'</td>';
                        $html .= '<td>R$ '.str_replace('.', ',', $produto['valor_venda_minimo']).'</td>';
                        $html .= '<td>R$ '.str_replace('.', ',', $produto['valor_venda']).'</td>';
                        $html .= '<td><button class="btn btn-success bt_add" title="Adicionar"><i class="fa fa-plus"></i></button></td>';
                        $html .= '</tr>';
                    }
                }
            }
        }

        if ( strlen($html) == 0 )
        {
            $html .= '<tr><td colspan="6">Nenhum resultado para: <b>'.$busca.'</b>.</td></tr>';
        }

        echo $html;
    }

    function ajax_precos()
    {
        $id = $this->input->post('id');
        $produto = $this->Produtos_model->obter($id);

        echo $produto['valor_compra'].';'.$produto['valor_venda_minimo'].';'.$produto['valor_venda'];
    }
}
