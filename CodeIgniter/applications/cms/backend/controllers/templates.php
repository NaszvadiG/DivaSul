<?php
include_once(APPPATH.'controllers/default_controller.php');
class Templates extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'cms_templates';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Templates_model');
        $this->Templates_model->set_table_name($this->table_name);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Templates';
        $this->module = '';
        $this->controller = 'templates';

        // Remove a coluna Ativo
        unset($this->colunas_default[parent::COLUNA_ATIVO]);
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Remove o template default (id = 0) e filtra pelos templates do site
        $this->where = array('site_id = '.$this->site_id);

        // Aplica um ORDER BY na listagem
        $this->ordens = array('titulo ASC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Título', // Descrição (texto impresso na tela)
                'coluna' => 'titulo', // Coluna no array de dados ($this->registros)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string' // Tipo (integer, date, string, boolean), usado no WHERE...
            )
        );

        parent::listar();
    }

    function editar($id=NULL)
    {
        $dados = array();
        if ( !$this->input->post('submit') && $id )
        {
            $dados['template'] = $this->Templates_model->obter($id);
        }
        else
        {
            $dados = $this->input->post();
        }

        // Validação
        $this->form_validation->set_rules('template[titulo]', 'Título', 'trim|required');
        $this->form_validation->set_rules('template[html]', 'Html', 'required');

        if ( $this->input->post('submit') &&
             $this->form_validation->run() )
        {
            $template = $dados['template'];
            $html_last = element('html', $this->Templates_model->obter($template['id']));
            if ( $html_last )
            {
                $template['html_last'] = $html_last;
            }
            $id = $this->Templates_model->salvar($template);

            redirect('templates');
        }
        else
        {
            $dados['erro'] = validation_errors();
        }

        $this->load->view('template_editar', $dados);
    }

    function baixar_html_template($template_id)
    {
        $this->load->model('Templates_model');
        $template = $this->Templates_model->obter($template_id);

        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$template['html'].'.html"');
        echo $template['html'];
    }
}
?>
