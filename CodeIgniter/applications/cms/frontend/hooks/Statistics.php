<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Statistics
{
    public function log_activity()
    {
        // We need an instance of CI as we will be using some CI classes
        $CI =& get_instance();
        // Next up, we want to know what page we're on, use the router class
        $data['class'] = $CI->router->class;
        $data['method'] = $CI->router->method;
        // We don't need it, but we'll log the URI just in case
        $data['uri'] = uri_string();
    }
}
?>
