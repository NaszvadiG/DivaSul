<?php
// Inclui a classe default_controller.php (não conseguimos por no autoload.)
include_once(APPPATH.'controllers/default_controller.php');
class Usuarios extends Default_controller
{
    function __construct()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'cms_usuarios';
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Usuarios_model');
        $this->Usuarios_model->set_table_name($this->table_name);

        // Altera o tamnho da coluna das ações
        $this->colunas_default[parent::COLUNA_ACOES]['tamanho'] = 75;
        // Ações
        $this->acoes = array_merge(array(
            array(
                'descricao' => 'Permissões', // Descrição
                'acao' => 'permissoes', // Função do controller que será chamada (ação)
                'icone' => 'arquivos/css/icons/wheel.png' // Imagem do botão
            ),
            array(
                'descricao' => 'Remover permissões',
                'acao' => 'remover_todas_permissoes',
                'icone' => 'arquivos/css/icons/locked.png'
            )
        ), $this->acoes);

        // Remove a coluna Ativo
        unset($this->colunas_default[parent::COLUNA_ATIVO]);

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Usuários';
        $this->module = '';
        $this->controller = 'usuarios';
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
                'descricao' => 'Usuário', // Descrição (texto impresso na tela)
                'align' => 'left', // Alinhamento da coluna (<td>)
                'coluna_filtravel' => true, // Se é ou não filtrável (adiciona, ou não, o campo de busca)
                'tipo' => 'string', // Tipo (integer, date, string, boolean), usado no WHERE...
                'coluna' => 'usuario' // Coluna no array de dados ($this->registros)
            )
        );

        parent::listar();
    }

    function editar($id=NULL)
    {
        // Array de dados para a view
        $dados = array();

        // Carrega a model
        if ( (int)$id > 0 )
        {
            $dados['registro'] = $usuario = $usuario_old = $this->Usuarios_model->obter($id);
        }

        // Obtém os dados
        if ( $this->input->post('submit') )
        {
            // se tem post, obtém do formulário
            $dados = $this->input->post();
            $usuario = $dados['registro'];
        }

        // Se tem post, salva os dados
        if ( $this->input->post('submit') )
        {
            $this->form_validation->set_rules('registro[nome]', 'Nome', 'trim|required');
            $this->form_validation->set_rules('registro[usuario]', 'Usuário', 'trim|required ');
            $this->form_validation->set_rules('registro[senha]', 'Senha', 'trim|required');
            if ( $this->form_validation->run() )
            {
                // Se digitou senha, MD5
                if ( strlen($usuario['senha']) > 0 )
                {
                     $usuario['senha'] = md5($usuario['senha']);
                }
                elseif ( strlen($usuario_old['usuario_id']) == 0 )
                {
                    // Sem senha não permite
                    $dados['erro'] = 'Você deve informar uma senha!';
                }
                else
                {
                    unset($usuario['senha']);
                }

                $id = $this->Usuarios_model->salvar($usuario);
                if ( $id )
                {
                    redirect('usuarios');
                }
                else
                {
                    $dados['erro'] = 'Falha ao criar usuário.';
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
        $campo['placeholder'] = 'Código do usuário';
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
        $campo['tamanho'] = 5;
        $campo['type'] = 'text';
        $campo['label'] = 'Nome';
        $campo['placeholder'] = 'Nome do usuário';
        $campo['value'] = $dados['registro']['nome'];
        $campo['required'] = true;
        $campos[] = $campo;
        // E-Mail
        $campo = array();
        $campo['id'] = 'email';
        $campo['name'] = 'registro[email]';
        $campo['tamanho'] = 5;
        $campo['type'] = 'text';
        $campo['label'] = 'E-Mail';
        $campo['placeholder'] = 'E-Mail do usuário';
        $campo['value'] = $dados['registro']['email'];
        $campo['required'] = true;
        $campos[] = $campo;
        // Nome de usuário
        $campo = array();
        $campo['id'] = 'usuario';
        $campo['name'] = 'registro[usuario]';
        $campo['tamanho'] = 3;
        $campo['type'] = 'text';
        $campo['label'] = 'Usuário';
        $campo['placeholder'] = 'Nome de usuário';
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
        $campo['placeholder'] = 'Senha do usuário';
        $campo['required'] = true;
        $campo['attrs'] = 'autocomplete="off"';
        $campos[] = $campo;

        // Campos do formulário
        $dados['campos'] = $campos;

        parent::load_view($dados);
    }

    function permissoes($id)
    {
        if ( empty($id) )
        {
            redirect('usuarios');
        }
        elseif ( $this->input->post('submit') )
        {
            $dados = $this->input->post();
            $this->Usuarios_model->remover_todas_permissoes($id);
            foreach ( $dados['permissoes'] as $permissao )
            {
                list($site_id, $modulo_id) = explode('-', $permissao);
                $this->Usuarios_model->adicionar_permissao($id, $site_id, $modulo_id);
            }
            redirect('usuarios');
        }
        else
        {
            $this->load->model('Sites_model');
            $this->load->model('Modulos_model');

            $dados['usuario'] = $this->Usuarios_model->obter($id);
            $dados['permissoes'] = $this->Usuarios_model->obter_permissoes($id);
            $dados['sites'] = $this->Sites_model->listar(array('order_by'=>'titulo ASC'));
            $dados['modulos'] = $this->Modulos_model->listar(array('order_by'=>'path ASC'));

            // Permissoes padrao
            $dados['permissoes_padrao'] = $this->Modulos_model->listar(array('where'=>array("padrao = '1'")));

            $this->load->view('usuarios_permissoes', $dados);
        }
    }

    function permissoes_padrao()
    {
        $dados = array();
        $this->load->model('Modulos_model');
        $dados['permissoes'] = $this->Modulos_model->listar(array('order_by'=>'path ASC'));
        $this->load->view('usuarios_permissoes_padrao', $dados);
    }

    function deletar($id)
    {
        $this->Usuarios_model->deletar($id);
        redirect('usuarios');
    }

    function remover_todas_permissoes($id)
    {
        $this->Usuarios_model->remover_todas_permissoes($id);
        redirect('usuarios');
    }

    /**
     * AJAX que altera o valor da coluna "padrao", que define se é ou não uma "default permission"
     * @param (int) $id Código do registro o qual será alterado o campo
     */
    function definir_permissao_padrao($id)
    {
        $this->load->model('Modulos_model');
        $dados = $this->Modulos_model->obter($id);

        $dados['padrao'] = ($dados['padrao'] == 1) ? 0 : 1;
        $ok = $this->Modulos_model->salvar($dados);

        echo is_int($ok);
    }
}
?>
