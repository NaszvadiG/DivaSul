<?php
/**
 * Este é o Controller padrão.
 * Possui:
 * Paginação;
 * Busca;
 * Ordenação pelas colunas;
 *
 * Utiliza o Model padrão.
 */
class Default_controller extends MX_Controller
{
    protected $pagina_atual;
    protected $total_de_registros = 0;
    protected $registros_por_pagina = 20;
    protected $registros; // Dados que popularão a "grid"

    // Usado para exibir, ou não, o botão de próxima página
    protected $tem_mais_registros;
    protected $desabilitar_paginacao = false;
    protected $desabilitar_buscar = false;
    protected $desabilitar_ordenacao = false;
    protected $desabilitar_inserir = false;

    // funções
    protected $funcao_inserir = 'inserir';
    protected $funcao_editar = 'editar';
    protected $funcao_remover = 'remover';
    protected $funcao_ativar_inativar = 'ativar_inativar';

    // Filtro
    protected $filtros = array();
    protected $where = array();
    protected $ordens = array();
    protected $ordem = '';

    // Título da página
    protected $titulo;

    // Define se foi clicado em buscar
    protected $busca;

    // Colunas default (id, ações)
    protected $colunas_default = array();

    // Exibir coluna ordem
    protected $exibir_coluna_ordem = false;

    // Se tem hierarquia
    protected $tem_hierarquia = false;

    // Colunas personalizadas
    protected $colunas = array();

    // Ações (default = editar, remover)
    protected $acoes;

    // Botoes extra adicionado antes do botão da busca
    protected $botoes;

    protected $table_name;
    protected $site_id;
    protected $usuario_id;
    protected $module;
    protected $controller;
    protected $function;
    protected $view = 'default_listar';
    protected $view_linha = 'default_listar_linha';

    // Constantes das colunas Default
    const COLUNA_ID = -100;
    const COLUNA_HIERARQUIA = -10;
    const COLUNA_ORDEM = 997;
    const COLUNA_ATIVO = 998;
    const COLUNA_ACOES = 999;
    const ACAO_EDITAR = 998;
    const ACAO_REMOVER = 999;

    /**
     * Construtor
     * @param (string) $table_name Nome da tabela principal desse controller
     */
    function __construct($table_name)
    {
        // Construtor do CI_Controller
        parent::__construct();

        // Define o site_id
        $this->site_id = $this->session->userdata('site_id');
        $this->usuario_id = $this->session->userdata('usuario_id');

        // Carrega o Model padrão, define a tabela
        $this->load->model('Default_model');
        if ( strlen($this->table_name) > 0 )
        {
            $this->default_model->set_table_name($this->table_name);
        }

        // Colunas padrão
        $this->colunas_default = array(
            self::COLUNA_ID => array(
                'descricao' => 'Código', // Descrição (label)
                'tamanho' => '35', // Largura (width)
                'coluna' => 'id', // Coluna no array $this->registros(alias do SQL) (posição do array associativo)
                'coluna_filtravel' => true, // Define se é ou não filtrável por esta coluna
                'align' => 'right', // Alinhamento da coluna (<td>)
                'tipo' => 'integer' // Tipo de dado (string, integer, date, boolean)
            ),
            self::COLUNA_ORDEM => array(
                'descricao' => 'Ordem',
                'tamanho' => '25',
                'coluna' => 'ordem',
                'funcao' => 'ordenar' // Função do controller que será chamada quando clicado nesse botao
            ),
            self::COLUNA_ATIVO => array(
                'descricao' => 'Ativo',
                'tamanho' => '20',
                'coluna' => 'ativo',
                'funcao' => 'ativo_inativo' // Função do controller que será chamada quando clicado nesse botao
            ),
            self::COLUNA_ACOES => array(
                'descricao' => 'Ações',
                'tamanho' => '50',
                'align' => 'center', // Alinhamento da coluna (<td>)
                'funcao' => 'acoes'
            )
            // Obs.: a chave do array de colunas deve ser numéria porque é feito ksort dele
        );

        // Acrescenta coluna hierarquia
        if ( $this->tem_hierarquia )
        {
            $this->colunas_default[self::COLUNA_HIERARQUIA] = array(
                'descricao' => '',
                'tamanho' => '35',
                'coluna' => 'tem_filhos',
                'coluna_sql' => '(SELECT tem_filhos(id))',
                'coluna_filtravel' => false,
                'align' => 'center',
                'tipo' => 'boolean'
            );
        }

        // Se não tiver definido para exibir a coluna ordem, remove-a
        if ( !$this->exibir_coluna_ordem )
        {
            unset($this->colunas_default[self::COLUNA_ORDEM]);
        }

        // Ações padrão
        $this->acoes = array(
            self::ACAO_EDITAR => array(
                'descricao' => 'Editar', // Descrição
                'acao' => 'editar', // Função do controller que será chamada (ação)
                'icone' => 'arquivos/css/icons/edit.png' // Imagem do botão
            ),
            self::ACAO_REMOVER => array(
                'descricao' => 'Remover',
                'acao' => 'remover',
                'icone' => 'arquivos/css/icons/delete.png',
                'onclick' => "return confirm('Excluir registro?');" // Parâmetro opcional: onclick
            ),
        );
    }

    /**
     * Define a tabela principal do controller
     */
    function set_table_name($table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * Função padrão: listar()
     */
    function index()
    {
        $this->listar();
    }

    /**
     * Inserir e editar usam a mesma função e mesma view
     */
    function inserir()
    {
        redirect($this->module.'/'.$this->controller.'/editar');
    }

    /**
     * Lista os registros na tela
     */
    function listar($dados=array())
    {
        // Se não tiver definido para exibir a coluna ordem, remove-a
        if ( !$this->exibir_coluna_ordem )
        {
            unset($this->colunas_default[self::COLUNA_ORDEM]);
        }

        // Define a tabela
        $this->default_model->set_table_name($this->table_name);

        if ( strlen($dados['function']) == 0 )
        {
            $dados['function'] = 'listar';
        }

        // Define a página atual
        $dados['pagina_atual'] = $dados['pagina_atual'] ? $dados['pagina_atual'] : $_POST['pagina_atual'];// $dados ou $_POST
        $dados['pagina_atual'] = $dados['pagina_atual'] ? $dados['pagina_atual'] : $this->uri->segment(4);// $dados ou URI
        $dados['pagina_atual'] = $dados['pagina_atual'] ? $dados['pagina_atual'] : 1; // $dados ou 1
        // Caso tenha algum filtro, troca para a primeira página
        if ( $this->busca )
        {
            $dados['pagina_atual'] = 1;
        }
        $this->pagina_atual = $dados['pagina_atual'];

        $dados['filtro'] = $_POST['filtro'];
        $dados['params'] = $_POST['params'];
        $params = MY_Utils::deserializar_dados_jquery($_POST['params']);
        // Organiza os dados recebidos via POST
        $filtros = (array)$this->processa_filtros($params);
        $dados['filtros'] = $filtros;
        // Deixa os filtros prontos pro SQL
        $this->filtros = $this->organiza_filtros($dados['filtros']);

        // Se for listagem, verifica se tem hierarquia, se for busca, remove hierarquia
        if ( $this->tem_hierarquia && count($filtros) == 0 )
        {
            $this->where[] = 'parent_id IS NULL';
        }
        else
        {
            $this->tem_hierarquia = false;
            unset($this->colunas_default[self::COLUNA_HIERARQUIA]);
        }

        // Define o título da tela
        $dados['titulo'] = $this->titulo;
        // Registros que irão ser exibidos na "grid"
        $dados['registros'] = $this->registros;

        // Colunas da tabela:
        $colunas = $this->colunas+$this->colunas_default; // array_merge(), mantendo as chaves numéricas
        ksort($colunas); // reordena
        $this->colunas = $colunas;

        // Ordem (quando clica no titulo de uma coluna) (listagem)
        $ordem = $_POST['ordem'];
        $this->ordem = $this->organiza_ordem($ordem);
        $dados['ordem'] = $this->ordem;

        $dados['params'] = $_POST['params'];
        $params = MY_Utils::deserializar_dados_jquery($_POST['params']);

        // Ordem (coluna na base de dados)
        $ordens = $this->processa_ordens($params);
        if ( is_array($ordens) && count($ordens) > 0 )
        {
            $dados['info'] = $this->altera_ordens($ordens);
        }

        /*
         * Filtro (busca/pesquisa)
         */
        $this->where = array_merge((array)$this->filtros, (array)$this->where);

        if ( !$this->desabilitar_paginacao )
        {
            // Obtém o total de registros
            $this->total_de_registros = $this->default_model->obter_total($this->where);
        }
        $dados['total_de_registros'] = $this->total_de_registros;
        $dados['registros_por_pagina'] = $this->registros_por_pagina;
        // (total de páginas - página atual) > 0
        $dados['tem_mais_paginas'] = (($this->total_de_registros / $this->registros_por_pagina)-$this->pagina_atual) > 0;

        // Ações
        ksort($this->acoes);
        $dados['acoes'] = $this->acoes;

        // Where
        $dados['where'] = $this->where;

        // Colunas
        $dados['colunas'] = $this->colunas;

        /*
         * Dados
         */
        // Obtém as colunas para o SQL
        $cols = array();
        foreach ( $colunas as $coluna )
        {
            if ( $coluna['coluna'] )
            {
                $col = ($coluna['coluna_sql']) ? $coluna['coluna_sql'].' AS '.$coluna['coluna'] : $coluna['coluna'];
                if ( strpos($col, 'TO_CHAR') !== false )
                {
                    $col = str_replace('TO_CHAR', 'DATE_FORMAT', $col);
                    $col = str_replace('DD/MM/YYYY', '%e/%m/%Y', $col);
                    $col = str_replace('HH24:MI:SS', '%H:%i:%s', $col);
                }
                $cols[] = $col;
            }
        }

        // Obtém os dados para popular a Grid
        if ( !isset($this->registros) )
        {
            $this->registros = $this->default_model->listar(array(
                'columns'  => $cols,
                'order_by' => $this->ordem,
                'limit'    => $this->registros_por_pagina,
                'offset'   => (($this->pagina_atual-1)*$this->registros_por_pagina),
                'where'    => $this->where
            ));
        }
        $dados['registros'] = $this->registros;
        // Fim da parte dos dados da grid

        // Define o modulo
        $dados['module'] = $this->module;
        // Define o controller
        $dados['controller'] = $this->controller;

        // Define botões extras antes do botão de busca
        $dados['botoes'] = $this->botoes;

        // Se tem hierarquia
        $dados['tem_hierarquia'] = $this->tem_hierarquia;

        // Desabilita paginação
        $dados['desabilitar_paginacao'] = $this->desabilitar_paginacao;
        // Desabilita a busca
        $dados['desabilitar_buscar'] = $this->desabilitar_buscar;
        // Desabilita a ordenacao
        $dados['desabilitar_ordenacao'] = $this->desabilitar_ordenacao;
        // Desabilita botão inserir
        $dados['desabilitar_inserir'] = $this->desabilitar_inserir;
        // Função inserir
        $dados['funcao_inserir'] = $this->funcao_inserir;
        // Função editar
        $dados['funcao_editar'] = $this->funcao_editar;
        // Função remover
        $dados['funcao_remover'] = $this->funcao_remover;
        // Função ativar_inativar
        $dados['funcao_ativar_inativar'] = $this->funcao_ativar_inativar;

        // View da linha
        $dados['view_linha'] = $this->view_linha;

        $this->load->view($this->view, $dados);
    }

    /**
     * Obtém os filtros das colunas
     * retorna um array: $array[<coluna>] = <valor>;
     */
    function processa_filtros($array_params)
    {
        $filtros = array();

        foreach ( (array)$array_params as $k => $param )
        {
            // Remove as ordens do array de params
            if ( !preg_match('/^ordem_[0-9]{1,}$/', $k) )
            {
                $col = $k;
                // Se for uma coluna SQL
                foreach ( $this->colunas as $coluna )
                {
                    if ( $coluna['coluna_sql'] == urldecode($k) )
                    {
                        $col = $coluna['coluna'];
                    }
                }
                $filtros[$col] = $param;
            }
        }

        return $filtros;
    }

    /**
     * Obtém as ordens da coluna ordem
     * retorna um array: $array[<id_pagina>] = <ordem>;
     */
    function processa_ordens($array_params)
    {
        $ordens = array();

        foreach ( (array)$array_params as $k => $ordem )
        {
            // Obtém as ordens do array de params
            if ( preg_match('/^ordem_[0-9]{1,}$/', $k) )
            {
                $ordens[str_replace('ordem_', '', $k)] = $ordem;
            }
        }

        return $ordens;
    }

    /**
     * Função que trabalha com o array $ordem e altera o ícone das colunas (seta pra cima, baixo ou sem seta)
     */
    function organiza_ordem($ordem)
    {
        // Ordens
        $ordens = explode(',', $ordem);
        // Coluna clicada
        $nova_ordem = array_pop($ordens);

        if ( strlen($nova_ordem) > 0 )
        {
            // Remove os iguais (caso clique duas vezes no ordenar)
            foreach ( $ordens as $k => $ordem )
            {
                if ( $nova_ordem == $ordem )
                {
                    unset($ordens[$k]);
                }
            }

            $nao_tinha = true;
            foreach ( $ordens as $k => $ordem )
            {
                // Se tem com ASC e não tem com DESC, adiciona com DESC
                if ( $nova_ordem.' ASC' == $ordem )
                {
                    $ordens[$k] = $nova_ordem.' DESC';
                    $nao_tinha = false;
                }
                // Se já tem com DESC, remove-o
                elseif ( $nova_ordem.' DESC' == $ordem )
                {
                    unset($ordens[$k]);
                    $nao_tinha = false;
                }

                if ( is_null($ordens[$k]) || strlen($ordens[$k]) == 0 )
                {
                    unset($ordens[$k]);
                }
            }

            // Se não tem a coluna, adiciona com ASC
            if ( $nao_tinha )
            {
                $ordens[] = $nova_ordem.' ASC';
            }
        }

        // Quando clica em busca, altera a página para 1
        if ( count($ordens) == 0 && strlen($nova_ordem) == 0 )
        {
            $this->busca = true;
        }

        if ( count($ordens) == 0 && strlen($nova_ordem) == 0 )
        {
            // Default: ordena por id DESC
            /*
            $ordens[] = 'id DESC';
             */
            $ordens = $this->ordens;
        }

        // Altera os icones
        $this->altera_icones_colunas($ordens);

        $ordem = implode(',', $ordens);

        return $ordem;
    }

    /**
     * Altera o ícone das colunas (seta para cima, para baixo, sem seta)
     */
    function altera_icones_colunas($ordens=array())
    {
        // Percorre os ordens
        foreach ( (array)$ordens as $ordem )
        {
            // Uma volta para ASC e uma para DESC
            foreach ( array('ASC', 'DESC') as $tipo )
            {
                // Se tem algum ordem
                if ( strpos($ordem, ' '.$tipo) )
                {
                    // Adiciona os ASC ou os DESC nas colunas filtradas (depois na view o CSS coloca as setas)
                    foreach ( $this->colunas as $k => $col )
                    {
                        // Porque uma coluna pode ser um alias no SQL, Ex.: data_formatada ordena por data
                        $col['coluna'] = $col['coluna_sql'] ? $col['coluna_sql'] : $col['coluna'];
                        if ( current(explode(' '.$tipo, $ordem))  == $col['coluna'] )
                        {
                            $this->colunas[$k]['tipo_ordem'] = $tipo;
                        }
                    }
                }
            }
        }
    }

    /**
     * Recebe o array:
     * $array['filtro1'] = 'valor1';
     * $array['filtro2'] = 2;
     * $array['filtro3'] = '12/12/2012';
     *
     * E retorna:
     * $array[0] = "filtro1 ILIKE '%valor1%'";
     * $array[1] = "filtro2 = '2'";
     * $array[2] = "filtro2 = '12/12/2012'";
     */
    function organiza_filtros($filtros)
    {
        $where = array();
        // Compara os filtros com as colunas e monta o SQL
        foreach ( $this->colunas as $coluna )
        {
            foreach ( $filtros as $k => $filtro )
            {
                // Casos onde é usado ALIAS no SQL
                $coluna['coluna'] = $coluna['coluna_sql'] ? $coluna['coluna_sql'] : $coluna['coluna'];
                if ( in_array($k, array($coluna['coluna'], $coluna['coluna_sql'])) )
                {
                    vd($k);
                    // Se for 't'
                    if ( in_array($filtro, array('true', 't')) )
                    {
                        $filtro = 1;
                    }
                    // Se for 'f'
                    if ( in_array($filtro, array('false', 'f')) )
                    {
                        $filtro = 0;
                    }
                    // Se for data
                    if ( (strlen($filtro) == 10) && preg_match("@^[0-9]{2}/[0-9]{2}/[0-9]{4}$@", $filtro) )
                    {
                        $filtro = implode('-', array_reverse(explode('/', $filtro)));
                    }

                    // Monta o SQL
                    // se for date => TO_CHAR(coluna, 'YYYY-MM-DD') = 'valor'
                    if ( $coluna['tipo'] == 'date' )
                    {
                        $where[] = "DATE_FORMAT(".$k.", '%Y-%m-%e') = '".trim($filtro)."'";
                    }
                    // se for string => coluna ILIKE '%valor%'
                    elseif ( $coluna['tipo'] == 'string' )
                    {
                        $where[] = "LOWER(".$coluna['coluna'].") LIKE LOWER('%".trim($filtro)."%')";
                    }
                    // caso contrario(integer, boolean) => coluna = 'valor'
                    else
                    {
                        $where[] = $k." = '".trim($filtro)."'";
                    }
                }
            }
        }

        return $where;
    }

    /**
     * Remove um registro
     * @param (int) $id Código do registro a ser removido
     */
    function remover($id)
    {
        $this->default_model->remover($id);

        redirect($this->module.'/'.$this->controller);
    }

    /**
     * AJAX que altera o status Ativo/Inativo
     * @param (int) $id Código do registro o qual será alterado o status.
     */
    function ativar_inativar($id)
    {
        $dados = $this->default_model->obter($id);

        $dados['ativo'] = ($dados['ativo'] == '1') ? '0' : '1';
        $ok = $this->default_model->salvar($dados);

        return is_int($ok);
    }

    /**
     * Altera as ordens (coluna ordem) dos registros
     * retorna um array com as informações de registro tal alterado para ordem tal.
     */
    function altera_ordens($ordens=array())
    {
        $array_info = array();

        foreach ( (array)$ordens as $registro_id => $registro_ordem )
        {
            $registro = $this->default_model->obter($registro_id);
            $ordem_antiga = $registro['ordem'];
            $registro['ordem'] = $registro_ordem;

            // Se não for a mesma ordem, salva
            if ( $registro_ordem != $ordem_antiga )
            {
                $ok = $this->default_model->salvar($registro);

                // Altera a ordem
                if ( $ok )
                {
                    $array_info[] = 'Alterado a ordem do registro '.$registro_id.' de '.$ordem_antiga.' para '.$registro_ordem;
                }
            }
        }

        return $array_info;
    }

    /**
     * Obtém o valor de uma coluna
     * @param integer $id Código do registro
     * @param string $coluna Coluna onde está o valor procurado
     */
    function obter_valor($id, $coluna)
    {
        $registro = $this->default_model->obter($id);
        echo $registro[$coluna];
    }

    /**
     * Obtém os registros filhos
     * @param integer $parent_id
     * @param string $colunas - Array urlencode, serializado e com base64
     * @param string $acoes - Array urlencode, serializado e com base64
     * @param string $where - Array urlencode, serializado e com base64
     * @param string $ordem - Array urlencode, serializado e com base64
     * @param integer $nivel_filho - Nível de hierarquia (quantos |- vão na frente)
     * @return HTML das linhas
     */
    function obter_filhos()
    {
        $module = $_POST['module'];
        $controller = $_POST['controller'];
        $parent_id = $_POST['parent_id'];
        $colunas = $_POST['colunas'];
        $acoes = $_POST['acoes'];
        $where = $_POST['where'];
        $funcao_editar = $_POST['funcao_editar'];
        $ordem = unserialize(base64_decode(urldecode($_POST['ordem'])));
        $nivel_filho = $_POST['nivel'];
        $view_linha = unserialize(base64_decode(urldecode($_POST['view_linha'])));
        $view = (strlen($view_linha) > 0) ? $view_linha : $this->view_linha;

        $dados = array();
        $dados['module'] = unserialize(base64_decode(urldecode($module)));
        $dados['controller'] = unserialize(base64_decode(urldecode($controller)));
        $dados['colunas'] = unserialize(base64_decode(urldecode($colunas)));
        $dados['acoes'] = unserialize(base64_decode(urldecode($acoes)));
        $dados['funcao_editar'] = unserialize(base64_decode(urldecode($funcao_editar)));
        $dados['prefixo_tr'] = 'sub_'.$parent_id;
        $dados['nivel'] = $nivel_filho+1;

        // Obtém as colunas para o SQL
        $cols = array();
        foreach ( (array)$dados['colunas'] as $coluna )
        {
            if ( $coluna['coluna'] )
            {
                $cols[] = ($coluna['coluna_sql']) ? $coluna['coluna_sql'].' AS '.$coluna['coluna'] : $coluna['coluna'];
            }
        }

        $where = unserialize(base64_decode(urldecode($where)));
        foreach ( (array)$where as $k => $wher )
        {
            if ( strpos($wher, 'parent_id') !== false )
            {
                $where[$k] = 'parent_id = '.$parent_id;
            }
        }
        $dados['registros'] = $this->default_model->listar(array(
            'columns'  => $cols,
            'order_by' => $ordem,
            'where'    => $where
        ));

        $this->load->view($view, $dados);
    }

    function load_view($dados=array())
    {
        $dados['module'] = $this->module;
        $dados['controller'] = $this->controller;
        $dados['titulo'] = $this->titulo;
        $dados['funcao_editar'] = $this->funcao_editar;
        $dados['funcao_listar'] = $this->funcao_listar;

        $this->load->view('default_editar', $dados);
    }
}
?>
