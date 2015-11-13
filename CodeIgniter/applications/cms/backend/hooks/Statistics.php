<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Statistics
{
    public function log_activity()
    {
        // We need an instance of CI as we will be using some CI classes
        $CI = &get_instance();

        // Start off with the session stuff we know
        $data = array();
        $data['usuario_id'] = $CI->session->userdata('usuario_id');
        $data['site_id'] = $CI->session->userdata('site_id');

        // Next up, we want to know what page we're on, use the router class
        $data['class'] = $CI->router->class;
        $data['method'] = $CI->router->method;

        // We don't need it, but we'll log the URI just in case
        $data['uri'] = uri_string();

        //$log = preg_replace('/\n\s*/', ' ', print_r($data, 1));
        //log_message('debug', $log);
    }
}
?>
