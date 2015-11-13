<?php
class Produtos extends MX_Controller
{
    private $path;
    private $produtos_por_pagina = 12;

    function __construct()
    {
        parent::__construct();

        $this->path = SERVERPATH;
        $this->path .= 'arquivos/produtos/';
        $this->load->model('Produtos_model');
    }

    function menu()
    {
        $dados = array();
        $dados['menu'] = $this->montar_menu(0);
        $busca = $this->session->userdata('busca');
        $dados['busca'] = $busca;

        $this->load->view('menu', $dados);
    }

    function menu_mobile()
    {
        $dados = array();
        $dados['menu'] = $this->montar_menu(0);

        $this->load->view('menu_mobile', $dados);
    }

    function menu_sidebar()
    {
        $dados = array();
        $link = $this->uri->segment(2);
        $categoria = $this->Produtos_model->get_categoria_by_link($link);
        if ( !is_array($categoria) || count($categoria) == 0 )
        {
            $categoria = $this->Produtos_model->get_categoria_by_link('produtos');
        }

        $dados['categoria'] = $categoria;
        $dados['categoria_pai'] = $categoria;
        $categorias = $this->Produtos_model->listar_categorias($categoria['id']);
        if ( !is_array($categorias) || count($categorias) == 0 )
        {
            $categorias = $this->Produtos_model->listar_categorias($categoria['parent_id']);
            $dados['categoria_pai'] = $this->Produtos_model->obter_categoria($categoria['parent_id']);
        }
        $dados['categorias'] = $categorias;

        $this->load->view('menu_sidebar', $dados);
    }

    function listar_home($categoria_id=0)
    {
        if ( $this->uri->segment(1) != 'buscar' )
        {
            //Limpa a busca
            //$this->session->set_userdata('busca', '');
        }

        $dados = array();
        $dados['path'] = $this->path;
        $params = array();
        $params['where'] = array();
        $params['where'][] = 'ativo = 1';
        $params['where'][] = 'destaque_principal = 1';
        if ( $categoria_id > 0 )
        {
            $categs[] = "categorias LIKE '%\'".$categoria_id."\'%'";
        }
        $categorias = $this->Produtos_model->listar_categorias($categoria_id);
        if ( count($categorias) > 0 )
        {
            foreach ( $categorias as $cat1 )
            {
                $categs[] = "categorias LIKE '%\'".$cat1['id']."\'%'";
                foreach ( $this->Produtos_model->listar_categorias($cat1['id']) as $cat2 )
                {
                    $categs[] = "categorias LIKE '%\'".$cat2['id']."\'%'";
                    foreach ( $this->Produtos_model->listar_categorias($cat2['id']) as $cat3 )
                    {
                        $categs[] = "categorias LIKE '%\'".$cat3['id']."\'%'";
                    }
                }
            }
            $params['where'][] = '('.implode(' OR ', $categs).')';
        }
        $params['order_by'] = 'destaque_principal DESC, destaque_categoria DESC, titulo ASC';
        $params['limit'] = 10;
        $dados['produtos'] = $this->Produtos_model->listar($params);

        $this->load->view('produtos_listar_home', $dados);
    }

    function listar($params)
    {
        if ( $this->uri->segment(1) != 'buscar' )
        {
            //Limpa a busca
            //$this->session->set_userdata('busca', '');
        }

        $dados = array();
        $dados['path'] = $this->path;
        $link = $this->uri->segment(2);
        $dados['link'] = $link;
        $categoria = $this->Produtos_model->get_categoria_by_link($link);
        $dados['categoria'] = $categoria;
        $categorias = $this->Produtos_model->get_sub_categorias($categoria['id']);
        $categorias[-1] = $categoria;
        $params = array();
        $params['limit'] = $this->produtos_por_pagina;
        $pagina = $this->uri->segment(3);
        $pagina = (int)$pagina > 1 ? $pagina : 1;
        $dados['pagina'] = $pagina;
        $params['offset'] = ($pagina-1)*$this->produtos_por_pagina;
        $params['where'] = array('ativo = 1');
        $params['order_by'] = 'destaque_principal DESC, destaque_categoria DESC, titulo ASC';
        $categs = array();
        foreach ( $categorias as $categoria )
        {
            $categs[] = "categorias LIKE '%\'".$categoria['id']."\'%'";
        }
        $params['where'][] = '('.implode(' OR ', $categs).')';
        $produtos = $this->Produtos_model->listar($params);
        $dados['produtos'] = $produtos;

        unset($params['offset']);
        $total_produtos = $this->Produtos_model->count($params);
        $dados['total_produtos'] = $total_produtos;
        $total_paginas = ceil($total_produtos/$this->produtos_por_pagina);
        $dados['total_paginas'] = $total_paginas;
        $tem_mais_paginas = ($pagina*$this->produtos_por_pagina) < $total_produtos;
        $dados['tem_mais_paginas'] = $tem_mais_paginas;

        $this->load->view('produtos_listar', $dados);
    }

    function exibir()
    {
        if ( $this->uri->segment(1) != 'buscar' )
        {
            //Limpa a busca
            //$this->session->set_userdata('busca', '');
        }
        $dados = array();
        $params = array();
        $link = $this->uri->segment(2);
        $produto = $this->Produtos_model->get_produto_by_link($link);
        if ( $link == '404' )
        {
            $params['where'] = array();
            $params['where'][] = 'ativo = 1';
            $params['limit'] = 10;
            $produtos_relacionados = $this->Produtos_model->listar($params);
            $dados['produtos_relacionados'] = $produtos_relacionados;

            $params['limit'] = 4;
            $produtos_aleatorios = $this->Produtos_model->listar($params);
            $dados['produtos_aleatorios'] = $produtos_aleatorios;
            $this->load->view('produto_404', $dados);
        }
        elseif ( is_array($produto) && count($produto) > 0 )
        {
            $dados['produto'] = $produto;
            $categorias = explode("','", substr($produto['categorias'],1,-1));
            if ( count($categorias) == 1 )
            {
                $categoria = $this->Produtos_model->obter_categoria($categorias[0]);
            }
            else
            {
                $categoria = $this->Produtos_model->obter_categoria(1);
            }
            $dados['categoria'] = $categoria;
            if ( $categoria['parent_id'] )
            {
                $categoria_pai = $this->Produtos_model->obter_categoria($categoria['parent_id']);
                if ( $categoria_pai['parent_id'] )
                {
                    $categoria_avo = $this->Produtos_model->obter_categoria($categoria_pai['parent_id']);
                }
            }
            $dados['categorias'] = array();
            if ( $categoria_avo['id'] )
            {
                $dados['categorias'][] = $categoria_avo;
            }
            if ( $categoria_pai['id'] )
            {
                $dados['categorias'][] = $categoria_pai;
            }
            $dados['categorias'][] = $categoria;

            $params['where'] = array();
            $params['where'][] = 'ativo = 1';
            $params['where'][] = 'id != '.$produto['id'];
            $categs = array();
            if ( count($categorias) > 0 )
            {
                foreach ( $categorias as $cat1 )
                {
                    $categs[] = "categorias LIKE '%\'".$cat1."\'%'";
                    foreach ( $this->Produtos_model->listar_categorias($cat1) as $cat2 )
                    {
                        $categs[] = "categorias LIKE '%\'".$cat2['id']."\'%'";
                        foreach ( $this->Produtos_model->listar_categorias($cat2['id']) as $cat3 )
                        {
                            $categs[] = "categorias LIKE '%\'".$cat3['id']."\'%'";
                        }
                    }
                }
                $params['where'][] = '('.implode(' OR ', $categs).')';
            }
            $params['order_by'] = 'destaque_principal DESC, destaque_categoria DESC, id DESC';
            $params['limit'] = 10;
            $produtos_relacionados = $this->Produtos_model->listar($params);
            $dados['produtos_relacionados'] = $produtos_relacionados;

            $params = array();
            $params['where'] = array();
            $params['where'][] = 'ativo = 1';
            $params['where'][] = 'id != '.$produto['id'];
            foreach ( $produtos_relacionados as $prod )
            {
                $params['where'][] = 'id != '.$prod['id'];
            }
            $params['order_by'] = 'rand()';
            $params['limit'] = 4;
            $produtos_aleatorios = $this->Produtos_model->listar($params);
            $dados['produtos_aleatorios'] = $produtos_aleatorios;
        }
        else
        {
            redirect('/produto/404/'.$link);
        }

        $this->load->view('produto_exibir', $dados);
    }

    function montar_menu($parent_id)
    {
        $menus = array();
        if ( isset($parent_id) )
        {
            $categorias = $this->Produtos_model->listar_categorias($parent_id);
            foreach ( $categorias as $categoria )
            {
                $menus[] = $categoria;
                $menus[count($menus)-1]['subs'] = $this->montar_menu($categoria['id']);
            }
        }
        return $menus;
    }

    function orcamento($params=array())
    {
        $ok = false;

        // Obtém o email destino do email do orcamento
        $email = $params['email'];
        $email='site@serigrafia-es.com.br';

        // dados do contato
        $orcamento = $this->input->post('orcamento');
        @$this->Produtos_model->salvar_orcamento($orcamento);

        // dados do produto em questão
        $produto = $this->Produtos_model->obter($orcamento['produto_id']);

        // se tem nome e email do cliente
        If ( strlen($orcamento['nome']) > 0 && strlen($orcamento['email']) > 0 )
        {
            // instancia a classe de envio de emails
            //$this->load->library('MY_PHPMailer');
            ///$mail = new MY_PHPMailer();//incia
            // de quem
            //$mail->SetFrom($orcamento['email'], mb_encode_mimeheader($orcamento['nome']));
            // para quem
            //$mail->AddAddress($email, mb_encode_mimeheader('Serigrafai El Shaddai'));
            // assunto
            $assunto = mb_encode_mimeheader('[El Shaddai] Solicitação de Orçamento');
            //$mail->Subject = $assunto;

            // mensagem
            $mensagem = 'Olá,<br>';
            $mensagem .= $orcamento['nome'].' se interessou pelo produto <a href="'.base_url('produto/'.$produto['link']).'" target="_blank">'.(strlen($produto['referencia'])>0?$produto['referencia']:$produto['codigo']).' - '.$produto['titulo'].'</a>:<br>';
            $mensagem .= '<br>';
            $mensagem .= 'Nome: '.$orcamento['nome'];
            $mensagem .= '<br>';
            $mensagem .= 'E-mail: '.$orcamento['email'];
            $mensagem .= '<br>';
            $mensagem .= 'Telefone: '.$orcamento['telefone'];
            $mensagem .= '<br>';
            $mensagem .= 'Cidade: '.$orcamento['cidade'].'/'.$orcamento['estado'];
            $mensagem .= '<br>';
            $mensagem .= 'Quantidade: '.$orcamento['quantidade'];
            $mensagem .= '<br>';
            $mensagem .= 'Mensagem: '.$orcamento['mensagem'];
            $mensagem .= '<br>';

            $mensagem = nl2br($mensagem);
            //$mail->MsgHTML($mensagem);
            //$ok = $mail->Send();

            $email_headers = implode("\n",array("From: $email", "Reply-To: $email", "Subject: $assunto","Return-Path: $email","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html; charset=UTF-8"));
            $ok = mail($email,$assunto,$mensagem,$email_headers);
        }
        echo $ok ? '1' : '0';
    }

    function imagem()
    {
        $id = $this->uri->segment(3);
        $imagem = $this->uri->segment(4);
        $imagem = base64_decode(urldecode($imagem));
        $imagem = $this->path.$id.'/'.$imagem;
        if ( $id > 0 && strlen(file_get_contents($imagem)) > 0 )
        {
            list($width, $height, $type, $attr) = getimagesize($imagem);
            $image = imagecreatefromjpeg($imagem);
            $newImage = imagecreatetruecolor(400, 400);
            imagecopyresized($newImage, $image, 0, 0, 0, 0, 400, 400, $width, $height);
            imagedestroy($image);
            header('Content-Type: image/jpeg');  
            imagejpeg($newImage); //you does not want to save.. just display
            imagedestroy($newImage); //but not needed, cause the script exit in next line and free the used memory
        }
        else
        {
            redirect('produtos');
        }
    }
}
?>
