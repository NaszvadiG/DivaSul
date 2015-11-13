<?php
class MY_Valida_email
{
    private $email;

    public function __construct($email=null)
    {
        if ( !is_null($email) )
        {
            $this->set_email($email);
        }
    }
    public function set_email($email)
    {
        $this->email = $email;
    }

    /**
     * Verifica se o e-mail é válido
     * @return boolean
     */
    public function valida()
    {
        $valido = false;

        $expressao = "^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+.([a-zA-Z]{2,4})$";

        if ( ereg($expressao, $this->email) )
        {
            $valido = true;
        }

        return $valido;
    }
}
?>

