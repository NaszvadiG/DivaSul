<?php
class MY_SoapClient extends SoapClient
{
    public function __construct($soap=null)
    {
        if( !is_null($soap) )
        {
            $CI =& get_instance();
            $CI->load->config('soap');
            $serverConfig = $CI->config->config[$soap];
            $this->key    = $serverConfig['key'];

            parent::__construct(null, $serverConfig);
        }
    }

    public function __call($name, $arguments)
    {
        $arguments[]= $this->key;

        $result = parent::__soapCall($name, $arguments);
        $result = unserialize(base64_decode($result));
        if ( $result instanceof Exception )
        {
            throw $result;
        }
        else
        {
            return $result;
        }
    }
}
?>
