<?php
class MY_FTP_Utils
{
    private $usuario;
    private $senha;
    private $ip_servidor;
    private $conexao;

    function __construct($config=null)
    {
        // Define os dados
        if ( $config )
        {
            $this->conectar($config);
        }
    }

    function definir_configuracoes($config)
    {
        // Obtém os dados
           $CI =& get_instance();
        $CI->load->config('ftp');

        $this->usuario = $CI->config->config[$config]['usuario'];
        $this->senha = $CI->config->config[$config]['senha'];
        $this->ip_servidor = $CI->config->config[$config]['servidor'];
    }

    function conectar($config)
    {
        $this->definir_configuracoes($config);

        $conexao = ftp_connect($this->ip_servidor) or die('Não foi possível se conectar com '.$this->ip_servidor);

        $sucesso = false;
        if ( ftp_login($conexao, $this->usuario, $this->senha) )
        {
            $this->conexao = $conexao;
            $sucesso = true;
        }

        return $sucesso;
    }

    function obter_conexao()
    {
        $conexao = $this->conexao;

        return $conexao;
    }

    function encerrar()
    {
        $ok = ftp_close($this->conexao);

        return $ok;
    }

    function enviar_arquivo($local, $remoto)
    {
        $this->remover_arquivo($remoto);
        $sucesso = ftp_put($this->conexao, $remoto, $local, FTP_BINARY);

        return $sucesso;
    }

    function listar_arquivos($diretorio_remoto)
    {
        $arquivos = ftp_nlist($this->conexao, $diretorio_remoto);

        return $arquivos;
    }

    function obter_arquivo($remoto, $local)
    {
        $sucesso = ftp_get($this->conexao, $local, $remoto, FTP_BINARY);

        return $sucesso;
    }

    function remover_arquivo($arquivo)
    {
        $sucesso = ftp_delete($this->conexao, $arquivo);

        return $sucesso;
    }
}
?>
