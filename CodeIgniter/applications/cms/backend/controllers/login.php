<?php
if ( !defined('BASEPATH') )
{
    exit('No direct script access allowed');
}

class Login extends CI_Controller
{
    function index()
    {
        if ( $this->session->userdata('usuario_id') )
        {
            $this->select_site();
            return;
        }

        $this->form_validation->set_rules('usuario', 'Nome de usuário', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required');
        if ( $this->form_validation->run() )
        {
            $this->load->model('Usuarios_model');
            $login = $this->Usuarios_model->autenticar($this->input->post('usuario'), md5($this->input->post('senha')));
            if ( $login )
            {
                $usuario = $this->db->get_where('cms_usuarios', array('id'=>$login))->row_array();
                if ( !empty($usuario) )
                {
                    $logado = $login;
                    $this->session->set_userdata('usuario_id', $login);
                    redirect('login/selecionar_site');
                }
                else
                {
                    $dados['erro'] = 'Não foi possível obter os dados do usuário '.$this->input->post('usuario').'.';
                }
            }
            else
            {
                $dados['erro'] = 'Usuário e senha não confere.';
            }
        }
        else
        {
            $dados['erro'] = validation_errors();
        }

        $this->session->keep_flashdata('redirect_url');
        $this->load->view('login', $dados);
    }

    function select_site()
    {
        if ( $this->session->userdata('site_id') )
        {
            $this->_redirect();
            return;
        }

        $usuario_id = $this->session->userdata('usuario_id');
        $sites = $this->db->select('s.id, s.titulo, s.icone')->distinct()->from('cms_permissoes p')->join('cms_sites s', 's.id = p.site_id', 'LEFT')->where('p.usuario_id', $usuario_id)->order_by('s.titulo')->get()->result_array();

        if ( count($sites) == 1 )
        {
            $site_id = $sites[0]['id'];
        }
        elseif ( count($sites) > 1 )
        {
            $site_id = $this->input->post('site_id');
            if ( empty($site_id) )
            {
                $this->session->keep_flashdata('redirect_url');

                $dados = array();
                $dados['sites'] = $sites;
                $this->load->view('login_selecao_de_site', $dados);
                return;
            }
        }
        else
        {
            $this->session->unset_userdata('usuario_id');
            $this->session->keep_flashdata('redirect_url');
            $this->load->view('login', array('erro'=>'Usuário sem permissões'));
            return;
        }

        $this->session->set_userdata('site_id', $site_id);

        //salva o nome do site selecionado na sessão
        $site_dir = current($this->db->select('dir')->where('id', $site_id)->get('cms_sites')->row_array());
        if ( $site_dir == 'public_html' ) // public_html é o "/"
        {
            $site_dir = '';
        }
        $this->session->set_userdata('site_dir', $site_dir);

        $this->_redirect();
    }

    function selecionar_site()
    {
        // Se cai no trocar site e não está logado, redireciona pro login
        if ( !$this->session->userdata('usuario_id') )
        {
            $this->_redirect();
            return;
        }
        else
        {
            // Limpa os dados do site e dos modulos, e redireciona pra seleção de site
            $this->session->unset_userdata('site_id');
            $this->select_site();
        }
    }

    function deslogar()
    {
        $this->session->unset_userdata('usuario_id');
        $this->session->unset_userdata('site_id');
        redirect('login');
    }

    function _redirect()
    {
        $url = $this->session->flashdata('redirect_url');
        redirect(!empty($url) ? $url : '');
    }
}
?>
