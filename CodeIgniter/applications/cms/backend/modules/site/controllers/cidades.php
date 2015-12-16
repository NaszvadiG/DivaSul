<?php
include_once(APPPATH . 'controllers/default_controller.php');
class Cidades extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'site_cidades';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Cidades_model');
        $this->load->model('Estados_model');

        // Define botoes extra antes da busca
        $this->botoes = array(
            array(
               'titulo' => 'Estados',
               'atributos_html' => array(
                   'class' => 'button search',
                   'title' => 'Listar estados',
                   'style' => 'float:right;',
                   'href' => base_url('site/estados/listar')
               )
            )
        );

        // Desabilita paginacao
        $this->exibir_coluna_ordem = false;
        // Remove coluna ativo
        unset($this->colunas_default[self::COLUNA_ATIVO]);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Cidades';
        $this->module = 'site';
        $this->controller = 'cidades';
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
                'descricao' => 'CEP', // Descrição (texto impresso na tela)
                'coluna' => 'cep', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => false,
                'tipo' => 'string'
            ),
            array(
                'descricao' => 'Estado', // Descrição (texto impresso na tela)
                'coluna' => 'estado', // Coluna no array de dados ($this->registros)
                'coluna_sql' => 'estado_id', // Coluna utilizada para ordenar/filtrar
                'sql' => "COALESCE((SELECT nome FROM site_estados WHERE id = site_cidades.estado_id),'-')",
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
            $dados['cidade'] = $cidade_old = $this->Cidades_model->obter($id);
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Se existe procede, se não exibe erro!
            $dados = $this->input->post();

            // Validação
            $this->form_validation->set_rules('cidade[nome]', 'Nome', 'trim|required');
            if ( $this->form_validation->run() )
            {
                if ( $dados['cidade']['cep'] == 0 )
                {
                    unset($dados['cidade']['cep']);
                }

                if ( $this->Cidades_model->salvar($dados['cidade']) > 0 )
                {
                    redirect('site/cidades/listar');
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

        $dados['estados'] = array();
        $estados = $this->Estados_model->listar();
        foreach ( $estados as $estado)
        {
            $dados['estados'][$estado['id']] = $estado['nome'];
        }

        $this->load->view('cidades_editar', $dados);
    }

    /**
     * Remove um tipo de cidade
     * @param $id Código do cidade
     */
    function remover($id)
    {
        // Carrega a model
        $this->table_name = 'site_cidades';
        $this->Cidades_model->set_table_name($this->table_name);

        if ( $this->Cidades_model->remover($id) )
        {
            redirect('site/cidades/listar');
        }
        else
        {
            $this->editar_cidade($id);
        }
    }
}