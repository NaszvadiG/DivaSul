<?php
// Inclui a classe default_controller.php (não conseguimos por no autoload.)
include_once(APPPATH . 'controllers/default_controller.php');

class Logs extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'cms_logs';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Logs_model');
        $this->Logs_model->set_table_name($this->table_name);
        $this->load->model('Usuarios_model');

        // Remove a coluna Ativo
        //unset($this->colunas_default[parent::COLUNA_ID]);
        unset($this->colunas_default[parent::COLUNA_ATIVO]);
        //unset($this->colunas_default[parent::COLUNA_ACOES]);
        //adiciona o "ver diff" em vez de editar
        $this->acoes[parent::ACAO_EDITAR] = array(
                'descricao' => 'Visualizar',
                'acao' => 'visualizar',
                'icone' => 'arquivos/css/icons/lupa.png'
        );
        // Desabilita o remover
        unset($this->acoes[parent::ACAO_REMOVER]);


        // Desabilita botao inserir
        $this->desabilitar_inserir = true;

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Logs';
        $this->module = '';
        $this->controller = 'logs';
    }

    function editar($id=null)
    {
        redirect('logs/listar');
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Ordenar por ID decrescente
        $this->ordens = array('id DESC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Tabela', // Descrição (texto impresso na tela)
                'coluna' => 'tabela', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true
            ),
            array(
                'descricao' => 'Campo',
                'coluna' => 'campo',
                'coluna_filtravel' => true
            ),
            array(
                'descricao' => 'Registro (ID)',
                'coluna' => 'id_registro',
                'coluna_filtravel' => true
            ),
            array(
                'descricao' => 'Data',
                'coluna' => 'data_hora',
                // coluna no SQL (caso use alias, ou subselect no SELECT)
                'coluna_sql' => 'data_hora', // Coluna utilizada para ordenar/filtrar
                // coluna que irá no SELECT
                'sql' => "TO_CHAR(data_hora, 'DD/MM/YYYY HH24:MI:SS')", // Coluna utilizada no SQL para obter os dados
                'tipo' => 'date',
                'coluna_filtravel' => true
            ),
            array(
                'descricao' => 'Usuário',
                'coluna' => 'usuario',
                'coluna_sql' => 'usuario_id',
                'sql' => "(SELECT nome FROM cms_usuarios WHERE usuario_id = cms_usuarios.id) || '(' || usuario_id || ')'"
            )
        );

        parent::listar();
    }

    function visualizar($id_log)
    {
        $dados = array();

        $log = $this->Logs_model->obter($id_log);
        if ( strlen($log['data']) == 19 )
        {
            $log['data_alteracao'] = date('d/m/Y', strtotime($log['data']));
            $log['hora_alteracao'] = date('H:i:s', strtotime($log['data']));
        }
        else
        {
            $log['data_alteracao'] = NULL;
            $log['hora_alteracao'] = NULL;
        }
        $dados['log'] = $log;
        $dados['usuario_id'] = $this->Usuarios_model->obter($log['usuario_id']);
        $dados['dados_anteriores'] = $this->Logs_model->obter_dados_anteriores($log['tabela'], $log['campo'], $log['id_registro']);

        $this->load->view('logs_visualizar', $dados);
    }
}
?>
