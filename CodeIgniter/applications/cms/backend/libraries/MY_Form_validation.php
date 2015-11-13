<?php
class MY_form_validation extends CI_form_validation
{
    function serialize_field($str, $field)
    {
        $CI = & get_instance();
        $componente = $CI->input->post($field);
        return serialize($componente);
    }

    function path_filter($path)
    {
        if ( preg_match('/^[a-z0-9_\/-]+$/i', $path) )
        {
            return TRUE;
        }
        else
        {
            $CI = & get_instance();
            $CI->form_validation->set_message('path_filter', 'Script não permitido');
            return FALSE;
        }
    }

    function unique_page_title($title)
    {
        $CI = &get_instance();
        $parent_id = intval(preg_replace('/[^0-9]/', '', element('parent_id', $CI->input->post('pagina'))));

        if ( $parent_id > 0 )
        {
            $id = $CI->uri->segment(3);
            $site_id = $CI->session->userdata('site_id');
            $CI->db->where('title', $title);
            $CI->db->where('site_id', $site_id);
            $CI->db->where('parent_id', $parent_id);
            if ( (int)$id > 0 )
            {
                $CI->db->where('id !=', $id);
            }

            $exists = $CI->db->get('cms_paginas')->row_array();
        }

        if ( !empty($exists) )
        {
            $CI->form_validation->set_message('unique_page_title', 'Esse %s já existe em outra página desse mesmo pai');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function unique_page_url($url)
    {
        $CI = &get_instance();
        $parent_id = intval(preg_replace('/[^0-9]/', '', element('parent_id', $CI->input->post('pagina'))));

        if ( $parent_id > 0 )
        {
            $id = $CI->uri->segment(3);
            $site_id = $CI->session->userdata('site_id');
            $CI->db->where('url', $url);
            $CI->db->where('site_id', $site_id);
            $CI->db->where('parent_id', $parent_id);
            if ( (int)$id > 0 )
            {
                $CI->db->where('id !=', $id);
            }

            $exists = $CI->db->get('cms_paginas')->row_array();
        }

        if ( !empty($exists) )
        {
            $CI->form_validation->set_message('unique_page_url', 'Essa %s já existe em outra página desse mesmo pai');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function parent_verify($parent_id)
    {
        $CI = & get_instance();
        $id = $CI->uri->segment(3);
        if (!empty($id) && $id==$parent_id)
        {
            $CI->form_validation->set_message('parent_verify', 'Não pode ser Pai de si mesmo');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function only_numbers($string)
    {
        $CI = & get_instance();
        $CI->form_validation->set_message('only_numbers', 'O campo %s não é válido.');
        $integer = intval($string);

        if ( $integer > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function valid_email($email)
    {
        $CI = & get_instance();
        $CI->form_validation->set_message('valid_email', 'O campo %s não possui um e-mail válido.');
        $CI->load->library('MY_Valida_email');
        $validador = new MY_Valida_email($email);

        return $validador->validar();
    }
}
?>
