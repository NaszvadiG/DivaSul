<?php
// Inclui a classe default_controller.php (não conseguimos por no autoload.)
include_once(APPPATH.'controllers/default_controller.php');
class Funcionarios extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'site_funcionarios';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Funcionarios_model');
        $this->Funcionarios_model->set_table_name($this->table_name);
        $this->load->model('Modulos_model');
        $this->load->model('Usuarios_model');
        $this->load->model('Cidades_model');

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Funcionários';
        $this->module = 'site';
        $this->controller = 'funcionarios';
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
                'descricao' => 'Usuário', // Descrição (texto impresso na tela)
                'coluna' => 'usuario', // Coluna no array de dados ($this->registros)
                'coluna_sql' => '(SELECT usuario FROM cms_usuarios WHERE id = site_funcionarios.usuario_id)',
                'coluna_filtravel' => true
            ),
            array(
                'descricao' => 'Cidade', // Descrição (texto impresso na tela)
                'coluna' => 'cidade', // Coluna no array de dados ($this->registros)
                'coluna_sql' => '(SELECT nome FROM site_cidades WHERE id = site_funcionarios.cidade_id)',
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
        $this->load->model('Funcionarios_model');
        if ( (int)$id > 0 )
        {
            $dados['registro'] = $funcionario = $funcionario_old = $this->Funcionarios_model->obter($id);
        }

        // Obtém os dados
        if ( $this->input->post('submit') )
        {
            // se tem post, obtém do formulário
            $dados = $this->input->post();
            $funcionario = $dados['registro'];
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            $this->form_validation->set_rules('registro[nome]', 'Nome', 'trim|required');
            $this->form_validation->set_rules('registro[telefone]', 'Telefone', 'trim|required');
            $this->form_validation->set_rules('registro[usuario]', 'Usuário', 'trim|required');
            if ( $this->form_validation->run() )
            {
                // Converte tudo pra maiusculo
                foreach ( $dados['registro'] as $k => $valor )
                {
                    if ( $k != 'senha' )
                    {
                        $dados['registro'][$k] = strtoupper($valor);
                    }
                }
                $funcionario = $dados['registro'];

                // Se digitou senha, MD5
                if ( strlen($funcionario['senha']) > 0 )
                {
                     $funcionario['senha'] = md5($funcionario['senha']);
                }
                else
                {
                    unset($funcionario['senha']);
                }

                // Se não tem usuário
                if ( strlen($funcionario_old['usuario_id']) == 0 )
                {
                    if ( !$funcionario['senha'] )
                    {
                        // Sem senha não permite
                        $dados['erro'] = 'Você deve informar uma senha!';
                    }
                }

                // Sem erro, segue o baile
                if ( strlen($dados['erro']) == 0 )
                {
                    //cria o usuario
                    $usuario = array();
                    if ( $funcionario_old['usuario_id'] )
                    {
                        $usuario['id'] = $funcionario_old['usuario_id'];
                    }
                    $usuario['nome'] = $funcionario['nome'];
                    $usuario['email'] = $funcionario['email'];
                    $usuario['usuario'] = $funcionario['usuario'];
                    if ( $funcionario['senha'] )
                    {
                        $usuario['senha'] = $funcionario['senha'];
                    }
                    $usuario_id = $this->Usuarios_model->salvar($usuario);

                    if ( $usuario_id )
                    {
                        // Permissoes padrao
                        $permissoes_padrao = $this->Modulos_model->listar(array('where'=>array("padrao = '1'")));
                        foreach ( $permissoes_padrao as $perm )
                        {
                            // Remove a permissão
                            $this->Usuarios_model->remover_permissao($usuario_id, $perm['id']);
                            // Adiciona a permissão
                            $this->Usuarios_model->adicionar_permissao($usuario_id, $this->site_id, $perm['id']);
                        }

                        $funcionario['usuario_id'] = $usuario_id;
                        unset($funcionario['usuario']);
                        unset($funcionario['senha']);
                        // Compatibilização
                        $funcionario['diaria'] = str_replace(',', '.', $funcionario['diaria']);
                        $funcionario['comissao'] = str_replace(',', '.', $funcionario['comissao']);
                        $id = $this->Funcionarios_model->salvar($funcionario);
                        if ( $id )
                        {
                            redirect('site/funcionarios');
                        }
                        else
                        {
                            $dados['erro'] = 'Falha ao criar funcionário.';
                        }
                    }
                    else
                    {
                        $dados['erro'] = 'Falha ao criar usuário.';
                    }
                }
            }
            else
            {
                $dados['erro'] = validation_errors();
            }
        }

        // Cidades
        $cidades = $this->Cidades_model->listar();
        $dados['cidades'] = array();
        foreach ( $cidades as $cidade )
        {
            $dados['cidades'][$cidade['id']] = $cidade['nome'];
        }

        // Compatibilização
        $dados['funcionario']['diaria'] = str_replace('.', ',', $dados['funcionario']['diaria']);
        $dados['funcionario']['comissao'] = str_replace('.', ',', $dados['funcionario']['comissao']);

        // Definição dos campos
        $campos = array();
        // Codigo
        $campo = array();
        $campo['id'] = 'id';
        $campo['name'] = 'registro[id]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'Código';
        $campo['placeholder'] = 'Código do funcionario';
        $campo['value'] = $dados['registro']['id'];
        if ( (int)$dados['registro']['id'] == 0 )
        {
            $campo['attrs'] = 'readonly';
        }
        $campos[] = $campo;
        // Nome
        $campo = array();
        $campo['id'] = 'nome';
        $campo['name'] = 'registro[nome]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Nome';
        $campo['placeholder'] = 'Nome do funcionário';
        $campo['value'] = $dados['registro']['nome'];
        $campo['required'] = true;
        $campos[] = $campo;
        // E-Mail
        $campo = array();
        $campo['id'] = 'email';
        $campo['name'] = 'registro[email]';
        $campo['tamanho'] = 4;
        $campo['type'] = 'text';
        $campo['label'] = 'E-Mail';
        $campo['placeholder'] = 'E-Mail do funcionário';
        $campo['value'] = $dados['registro']['email'];
        $campo['required'] = true;
        $campos[] = $campo;
        // RG
        $campo = array();
        $campo['id'] = 'rg';
        $campo['name'] = 'registro[rg]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'RG';
        $campo['placeholder'] = 'RG do funcionário';
        $campo['value'] = $dados['registro']['rg'];
        $campo['required'] = true;
        $campos[] = $campo;
        // CPF
        $campo = array();
        $campo['id'] = 'cpf';
        $campo['name'] = 'registro[cpf]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'CPF';
        $campo['placeholder'] = 'CPF do funcionário';
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
        $campo['placeholder'] = 'Data de nascimento do funcionário';
        $campo['value'] = $dados['registro']['data_nascimento'];
        $campos[] = $campo;
        // Telefone do funcionario
        $campo = array();
        $campo['id'] = 'telefone';
        $campo['name'] = 'registro[telefone]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Telefone';
        $campo['placeholder'] = 'Telefone do funcionário';
        $campo['value'] = $dados['registro']['telefone'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Telefone secundario do funcionario
        $campo = array();
        $campo['id'] = 'telefone2';
        $campo['name'] = 'registro[telefone2]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Telefone 2';
        $campo['placeholder'] = 'Telefone secundário do funcionário';
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
        $campo['placeholder'] = 'Cidade do funcionário';
        $campo['value'] = $dados['registro']['cidade_id'];
        $campo['options'] = $dados['cidades'];
        $campo['required'] = true;
        $campos[] = $campo;
        // CEP do funcionario
        $campo = array();
        $campo['id'] = 'cep';
        $campo['name'] = 'registro[cep]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'CEP';
        $campo['placeholder'] = 'CEP funcionário';
        $campo['value'] = $dados['registro']['cep'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Bairro do funcionario
        $campo = array();
        $campo['id'] = 'bairro';
        $campo['name'] = 'registro[bairro]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Bairro';
        $campo['placeholder'] = 'Bairro do funcionário';
        $campo['value'] = $dados['registro']['bairro'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Endereço do funcionario
        $campo = array();
        $campo['id'] = 'endereco';
        $campo['name'] = 'registro[endereco]';
        $campo['tamanho'] = 4;
        $campo['type'] = 'text';
        $campo['label'] = 'Endereço';
        $campo['placeholder'] = 'Endereço do funcionário';
        $campo['value'] = $dados['registro']['endereco'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Número do endereço do funcionario
        $campo = array();
        $campo['id'] = 'numero';
        $campo['name'] = 'registro[numero]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'Número';
        $campo['placeholder'] = 'Número do endereço do funcionário';
        $campo['value'] = $dados['registro']['numero'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Complemento do endereço do funcionario
        $campo = array();
        $campo['id'] = 'complemento';
        $campo['name'] = 'registro[complemento]';
        $campo['tamanho'] = 6;
        $campo['type'] = 'text';
        $campo['label'] = 'Complemento';
        $campo['placeholder'] = 'Compemento do endereço do funcionário';
        $campo['value'] = $dados['registro']['complemento'];
        $campos[] = $campo;
        // Valor da diária
        $campo = array();
        $campo['id'] = 'diaria';
        $campo['name'] = 'registro[diaria]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'number';
        $campo['label'] = 'Diária';
        $campo['placeholder'] = 'Valor da diaria do funcionário';
        $campo['value'] = $dados['registro']['diaria'];
        $campo['attrs'] = 'pattern="^\d+(\.|\,)\d{2}$" step="any"';
        $campo['required'] = true;
        $campo['pre'] = '<span class="input-group-addon">R$</span>';
        $campos[] = $campo;
        // Comissão
        $campo = array();
        $campo['id'] = 'comissao';
        $campo['name'] = 'registro[comissao]';
        $campo['tamanho'] = 2;
        $campo['type'] = 'number';
        $campo['label'] = 'Comissão';
        $campo['placeholder'] = 'Comissão do funcionário';
        $campo['value'] = $dados['registro']['comissao'];
        $campo['attrs'] = 'pattern="^\d+(\.|\,)\d{2}$" step="any"';
        $campo['required'] = true;
        $campo['pos'] = '<span class="input-group-addon">%</span>';
        $campos[] = $campo;
        // Nome de usuário
        $campo = array();
        $campo['id'] = 'usuario';
        $campo['name'] = 'registro[usuario]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Usuário';
        $campo['placeholder'] = 'Nome de usuário funcionário';
        $campo['value'] = $dados['registro']['usuario'];
        $campo['required'] = true;
        $campo['attrs'] = 'autocomplete="off"';
        $campos[] = $campo;
        // Senha
        $campo = array();
        $campo['id'] = 'senha';
        $campo['name'] = 'registro[senha]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'password';
        $campo['label'] = 'Senha';
        $campo['placeholder'] = 'Senha do funcionário';
        $campo['required'] = true;
        $campo['attrs'] = 'autocomplete="off"';
        $campos[] = $campo;

        // Campos do formulário
        $dados['campos'] = $campos;

        parent::load_view($dados);
    }
}
