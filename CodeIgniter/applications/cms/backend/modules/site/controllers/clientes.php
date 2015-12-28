<?php
// Inclui a classe default_controller.php (não conseguimos por no autoload.)
include_once(APPPATH.'controllers/default_controller.php');
class Clientes extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'site_clientes';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Clientes_model');
        $this->Clientes_model->set_table_name($this->table_name);
        $this->load->model('Cidades_model');
        $this->load->model('Funcionarios_model');

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Clientes';
        $this->module = 'site';
        $this->controller = 'clientes';
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar($pagina_atual=1)
    {
        // Define a página que está
        $this->pagina_atual = $pagina_atual;

        // Aplica um ORDER BY na listagem
        $this->ordens = array('nome ASC'); // ou array('campo DESC');

        // Define as colunas da tabela de listagem (Default já tem ID, Ativo, Ações)
        $this->colunas = array(
            array(
                'descricao' => 'Nome', // Descrição (texto impresso na tela)
                'align' => 'left', // Alinhamento da coluna (<td>)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string', // Tipo (integer, date, string, boolean), usado no WHERE...
                'coluna' => 'nome' // Coluna no array de dados ($this->registros)
            ),
            array(
                'descricao' => 'E-mail', // Descrição (texto impresso na tela)
                'align' => 'left', // Alinhamento da coluna (<td>)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string', // Tipo (integer, date, string, boolean), usado no WHERE...
                'coluna' => 'email' // Coluna no array de dados ($this->registros)
            ),
            array(
                'descricao' => 'Telefone', // Descrição (texto impresso na tela)
                'align' => 'left', // Alinhamento da coluna (<td>)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string', // Tipo (integer, date, string, boolean), usado no WHERE...
                'coluna' => 'telefone' // Coluna no array de dados ($this->registros)
            ),
            array(
                'descricao' => 'Cidade', // Descrição (texto impresso na tela)
                'coluna' => 'cidade', // Coluna no array de dados ($this->registros)
                'coluna_sql' => '(SELECT nome FROM site_cidades WHERE id = site_clientes.cidade_id)',
                'coluna_filtravel' => true
            )
        );

        parent::listar();
    }

    function editar($id=NULL)
    {
        // Array de dados para a view
        $dados = array();

        // Carrega a model
        $this->load->model('Clientes_model');
        if ( (int)$id > 0 )
        {
            $dados['registro'] = $cliente = $this->Clientes_model->obter($id);
        }

        // Obtém os dados
        if ( $this->input->post('submit') )
        {
            // se tem post, obtém do formulário
            $dados = $this->input->post();
            $cliente = $dados['registro'];
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            // Validação
            $this->form_validation->set_rules('registro[nome]', 'Nome', 'trim|required');
            $this->form_validation->set_rules('registro[telefone]', 'Telefone', 'trim|required');
            $this->form_validation->set_rules('registro[cidade_id]', 'Cidade', 'trim|required');
            if ( $this->form_validation->run() )
            {
                // Converte tudo pra maiusculo
                foreach ( $dados['registro'] as $k => $valor )
                {
                    $dados['registro'][$k] = strtoupper($valor);
                }
                $cliente = $dados['registro'];

                $id = $this->Clientes_model->salvar($cliente);
                if ( $id )
                {
                    redirect('site/clientes');
                }
                else
                {
                    $dados['erro'] = 'Falha ao criar funcionário.';
                }
            }
            else
            {
                $dados['erro'] = validation_errors();
            }
        }

        // Definição dos campos
        $campos = array();
        // Codigo
        $campo = array();
        $campo['id'] = 'id';
        $campo['name'] = 'registro[id]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'Código';
        $campo['placeholder'] = 'Código do cliente';
        $campo['value'] = $dados['registro']['id'];
        if ( (int)$dados['registro']['id'] == 0 )
        {
            $campo['attrs'] = 'readonly';
        }
        $campos[] = $campo;
        // Cliente de
        $campo = array();
        $funcionarios = $this->Funcionarios_model->listar();
        $dados['funcionarios'] = array();
        foreach ( $funcionarios as $funcionario )
        {
            $dados['funcionarios'][$funcionario['id']] = $funcionario['nome'];
        }
        $campo['id'] = 'funcionario_id';
        $campo['name'] = 'registro[funcionario_id]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Vendedor';
        $campo['placeholder'] = 'Cliente de ';
        $campo['value'] = $dados['registro']['funcionario_id'];
        if ( !$campo['value'] )
        {
            $funcionario = $this->Funcionarios_model->obter_por_usuario_id($this->usuario_id);
            if ( is_array($funcionario) && $funcionario['id'] )
            {
                $campo['value'] = $funcionario['id'];
            }
        }
        $campo['options'] = $dados['funcionarios'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Nome do cliente
        $campo = array();
        $campo['id'] = 'nome';
        $campo['name'] = 'registro[nome]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Nome';
        $campo['placeholder'] = 'Nome do cliente';
        $campo['value'] = $dados['registro']['nome'];
        $campo['required'] = true;
        $campos[] = $campo;
        // E-Mail do cliente
        $campo = array();
        $campo['id'] = 'email';
        $campo['name'] = 'registro[email]';
        $campo['tamanho'] = 4;
        $campo['type'] = 'text';
        $campo['label'] = 'E-Mail';
        $campo['placeholder'] = 'E-Mail do cliente';
        $campo['value'] = $dados['registro']['email'];
        $campo['required'] = true;
        $campos[] = $campo;
        // RG do cliente
        $campo = array();
        $campo['id'] = 'rg';
        $campo['name'] = 'registro[rg]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'RG';
        $campo['placeholder'] = 'RG do cliente';
        $campo['value'] = $dados['registro']['rg'];
        $campo['required'] = true;
        $campos[] = $campo;
        // CPF do cliente
        $campo = array();
        $campo['id'] = 'cpf';
        $campo['name'] = 'registro[cpf]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'CPF';
        $campo['placeholder'] = 'CPF do cliente';
        $campo['value'] = $dados['registro']['cpf'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Data de nascimento
        $campo = array();
        $campo['id'] = 'data_nascimento';
        $campo['name'] = 'registro[data_nascimento]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'date';
        $campo['label'] = 'Data de Nascimento';
        $campo['placeholder'] = 'Data de nascimento do cliente';
        $campo['value'] = $dados['registro']['data_nascimento'];
        $campos[] = $campo;
        // Telefone do cliente
        $campo = array();
        $campo['id'] = 'telefone';
        $campo['name'] = 'registro[telefone]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Telefone';
        $campo['placeholder'] = 'Telefone do cliente';
        $campo['value'] = $dados['registro']['telefone'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Telefone secundario do cliente
        $campo = array();
        $campo['id'] = 'telefone2';
        $campo['name'] = 'registro[telefone2]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Telefone 2';
        $campo['placeholder'] = 'Telefone secundário do cliente';
        $campo['value'] = $dados['registro']['telefone2'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Cidades
        $campo = array();
        $cidades = $this->Cidades_model->listar();
        $dados['cidades'] = array();
        foreach ( $cidades as $cidade )
        {
            $dados['cidades'][$cidade['id']] = $cidade['nome'];
        }
        $campo['id'] = 'cidade_id';
        $campo['name'] = 'registro[cidade_id]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Cidade';
        $campo['placeholder'] = 'Cidade do cliente';
        $campo['value'] = $dados['registro']['cidade_id'];
        $campo['options'] = $dados['cidades'];
        $campo['required'] = true;
        $campos[] = $campo;
        // CEP do cliente
        $campo = array();
        $campo['id'] = 'cep';
        $campo['name'] = 'registro[cep]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'CEP';
        $campo['placeholder'] = 'CEP cliente';
        $campo['value'] = $dados['registro']['cep'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Bairro do cliente
        $campo = array();
        $campo['id'] = 'bairro';
        $campo['name'] = 'registro[bairro]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Bairro';
        $campo['placeholder'] = 'Bairro do cliente';
        $campo['value'] = $dados['registro']['bairro'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Endereço do cliente
        $campo = array();
        $campo['id'] = 'endereco';
        $campo['name'] = 'registro[endereco]';
        $campo['tamanho'] = 4;
        $campo['type'] = 'text';
        $campo['label'] = 'Endereço';
        $campo['placeholder'] = 'Endereço do cliente';
        $campo['value'] = $dados['registro']['endereco'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Número do endereço do cliente
        $campo = array();
        $campo['id'] = 'numero';
        $campo['name'] = 'registro[numero]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'Número';
        $campo['placeholder'] = 'Número do endereço do cliente';
        $campo['value'] = $dados['registro']['numero'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Complemento do endereço do cliente
        $campo = array();
        $campo['id'] = 'complemento';
        $campo['name'] = 'registro[complemento]';
        $campo['tamanho'] = 6;
        $campo['type'] = 'text';
        $campo['label'] = 'Complemento';
        $campo['placeholder'] = 'Compemento do endereço do cliente';
        $campo['value'] = $dados['registro']['complemento'];
        $campos[] = $campo;

        // Campos do formulário
        $dados['campos'] = $campos;

        parent::load_view($dados);
    }
}
