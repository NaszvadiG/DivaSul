<?php
include_once(APPPATH . 'controllers/default_controller.php');
class Estados extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'site_estados';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Estados_model');
        $this->load->model('Paises_model');

        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
               'titulo' => 'Países',
               'atributos_html' => array(
                   'class' => 'button search',
                   'title' => 'Listar países',
                   'style' => 'float:right;',
                   'href' => base_url('site/paises/listar')
               )
            ),
            array(
               'titulo' => 'Voltar para cidades',
               'atributos_html' => array(
                   'class' => 'button search',
                   'title' => 'Voltar para cidades',
                   'style' => 'float:right;',
                   'href' => base_url('site/cidades/listar')
               )
            )
        );

        // Desabilita paginacao
        $this->exibir_coluna_ordem = false;
        // Remove coluna ativo
        unset($this->colunas_default[self::COLUNA_ATIVO]);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Estados';
        $this->module = 'site';
        $this->controller = 'estados';
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar()
    {
        // Aplica um ORDER BY na listagem
        $this->ordens = array('nome ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Nome', // Descrição (texto impresso na tela)
                'coluna' => 'nome', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Sigla', // Descrição (texto impresso na tela)
                'coluna' => 'sigla', // Coluna no array de dados ($this->registros)
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'País', // Descrição (texto impresso na tela)
                'coluna' => 'pais', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'pais_id', // Coluna utilizada para ordenar/filtrar
                'sql' => "COALESCE((SELECT nome FROM site_paises WHERE id = site_estados.pais_id),'-')",
                'coluna_filtravel' => true,
                'tamanho' => 45
            )
        );

        parent::listar($dados);
    }

    function editar($id=null)
    {
        // Array de dados para a view
        $dados = array();

        // Obtém os dados
        if ( $id )
        {
            // se não tem post e tem id, obtém da base de dados
            $dados['estado'] = $estado_old = $this->Estados_model->obter($id);
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Se existe procede, se não exibe erro!
            $dados = $this->input->post();

            // Validação
            $this->form_validation->set_rules('estado[nome]', 'Nome', 'trim|required');
            if ( $this->form_validation->run() )
            {
                if ( $this->Estados_model->salvar($dados['estado']) > 0 )
                {
                    redirect('site/estados/listar');
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

        $dados['paises'] = array();
        $paises = $this->Paises_model->listar();
        foreach ( $paises as $pais )
        {
            $dados['paises'][$pais['id']] = $pais['nome'];
        }

        $this->load->view('estados_editar', $dados);
    }

    /**
     * Remove um tipo de estado
     * @param $id Código do estado
     */
    function remover($id)
    {
        // Carrega a model
        $this->table_name = 'site_estados';
        $this->Estados_model->set_table_name($this->table_name);

        if ( $this->Estados_model->remover($id) )
        {
            redirect('site/estados/listar');
        }
        else
        {
            $this->editar($id);
        }
    }
}