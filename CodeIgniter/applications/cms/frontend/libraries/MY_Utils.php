<?php
class MY_Utils
{
    function quebrar_texto($texto, $tamanho)
    {
        $string = trim(strip_tags($texto));
        $string = substr($string, 0, $tamanho);
        $string = substr($string, 0, strrpos($string, ' '));

        return $string;
    }

    /**
     * Script para remover acentos e caracteres especiais:
     * @param String $oldString - String a ser "limpa"
     * @param bool $canBeNull - Permite que o retorno seja vazio
     * @return String
    */
    public static function removeSpecialChars($oldString, $canBeNull=false)
    {
        // Se corrige os acentos com iso, taca iso
        if ( strlen($oldString) > strlen(utf8_decode($oldString)) )
        {
            $oldString = utf8_decode($oldString);
        }

        /*
         * A função "strtr" substitui os caracteres acentuados pelos não acentuados.
         * A função "ereg_replace" utiliza uma expressão regular que remove todos os
         * caracteres que não são letras, números e são diferentes de "_" (underscore).
         */
        $newString = preg_replace("/[^.a-zA-Z0-9_-]/", "-", strtr(trim($oldString), "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));

        if ( !(strlen($newString) > 0) && !$canBeNull )
        {
            $newString = 'nome_invalido';
        }
    
        return $newString;
    }

    public static function retira_tags($texto)
    {
        if (!empty($texto)) $texto = preg_replace('/<span class="legenda.*?<\/span>/s', '', $texto);
        if (!empty($texto)) $texto = preg_replace('/<script[^>]*>.*?<\/script>/s', '', $texto);
        if (!empty($texto)) $texto = preg_replace('/<style[^>]*>.*?<\/style>/s', '', $texto);
        if (!empty($texto)) $texto = preg_replace('/<!--.*?-->/s', '', $texto);
        if (!empty($texto)) $texto = preg_replace('/<.*?>/s', ' ', $texto);
        if (!empty($texto)) $texto = preg_replace('/[\n ]/', ' ', $texto);
        if (!empty($texto)) $texto = preg_replace('/<.?a[^>]*>/s', ' ', $texto);
        if (!empty($texto)) $texto = preg_replace('/{.*}/s', ' ', $texto);

        return $texto;
    }


    /**
     * Função para calcular as novas dimensões de uma imagem
     * @param $altura Altura atual da imagem
     * @param $largura Largura atual da imagem
     * @param $alturaMax Altura máxima
     * @param $larguraMax Largura máxima
     * @return array Array com as novas dimensões ([0] altura e [1] largura
     */
    public static function calcular_novas_dimensoes($altura, $largura, $alturaMax=null, $larguraMax=null)
    {
        $nova_largura = $largura;
        $nova_altura = $altura;

        // Se a largura é maior ou igual a altura (landascape ou quadrado)
        if ( $nova_largura >= $nova_altura )
        {
            // Se definido largura máxima
            if  ( $larguraMax )
            {
                // Se a largura for maior que a largura máxima definida
                if ( $nova_largura > $larguraMax )
                {
                    $nova_largura = $larguraMax;
                    $nova_altura = ($nova_largura / $largura) * $nova_altura;
                }
            }
        }
        else // Se a altura for maior que a largura (portrait)
        {
            // Se definido altura máxima
            if ( $alturaMax )
            {
                // Se a altura for maior que a altura máxima definida
                if ( $nova_altura > $alturaMax )
                {
                    $nova_altura = $alturaMax;
                    $nova_largura = ($nova_altura / $altura) * $nova_largura;
                }
            }
        }

        return array( (int)$nova_altura,
                      (int)$nova_largura );
    }


    /**
     * Função para calcular as novas dimensões de uma imagem
     * @param $altura Altura atual da imagem
     * @param $largura Largura atual da imagem
     * @param $alturaMin Altura mínima
     * @param $larguraMin Largura mínima
     * @return array Array com as novas dimensões ([0] altura e [1] largura)
     */
    public static function calcular_novas_dimensoes_2($altura, $largura, $alturaMin=null, $larguraMin=null)
    {
        $nova_largura = $larguraMin;
        $nova_altura = ($nova_largura / $largura) * $altura;

        if ( $nova_altura < $alturaMin )
        {
            $nova_altura = $alturaMin;
            $nova_largura = ($nova_altura / $altura) * $largura;
        }

        return array( (int)$nova_altura,
                      (int)$nova_largura );
    }

    /**
     * Importa uma imagem JPEG pela URL
     */
    public static function LoadJPEG($imgURL)
    {
        ##-- Get Image file from Port 80 --##
        $fp = fopen($imgURL, "r");
        $imageFile = fread($fp, 3000000);
        fclose($fp);

        ##-- Create a temporary file on disk --##
        $tmpfname = tempnam("/tmp", "IMG");

        ##-- Put image data into the temp file --##
        $fp = fopen($tmpfname, "w");
        fwrite($fp, $imageFile);
        fclose($fp);

        ##-- Load Image from Disk with GD library --##
        $im = imagecreatefromjpeg($tmpfname);

        ##-- Delete Temporary File --##
        unlink($tmpfname);

        ##-- Check for errors --##
        if ( !$im )
        {
            print "Não foi possível criar a imagem JPEG a partir da URL: $imgURL";
        }

        return $im;
    }

    /**
     * Converte um array do PHP para um array do psql
     */
    public static function to_pg_array($phpArray)
    {
        $set = $phpArray;
        settype($set, 'array'); // can be called with a scalar or array
        $result = array();
        foreach ( $set as $t )
        {
            if ( is_array($t) )
            {
                $result[] = $this->to_pg_array($t);
            }
            else
            {
                // Escapa as aspas duplas
                $t = str_replace('"', '\\"', $t);
                // Cola aspas nos valores não numéricos
                if ( !is_numeric($t) )
                {
                    $t = "'" . $t . "'";
                }
                $result[] = $t;
            }
        }

        //adiciona / antes das aspas
        $result =  str_replace('\'', '"', $result);


        // Adiciona as "{}" e retorna como string
        return '{' . implode(",", $result) . '}';
    }

    /**
     * Converte um array do psql para um array do PHP
     */
    public static function to_php_array($psqlArray, &$output, $limit = false, $offset = 1)
    {
        if( false === $limit )
        {
            $limit = strlen($text)-1;
            $output = array();
        }

        if( '{}' != $text )
        do
        {
            if ( '{' != $text{$offset} )
            {
                preg_match("/(\\{?\"([^\"\\\\]|\\\\.)*\"|[^,{}]+)+([,}]+)/", $text, $match, 0, $offset);
                $offset += strlen($match[0]);
                $output[] = ('"' != $match[1]{0} ? $match[1] : stripcslashes(substr($match[1], 1, -1)));

                if ( '},' == $match[3] )
                {
                    return $offset;
                }
            }
            else
            {
                $offset = to_php_array( $text, $output[], $limit, $offset+1 );
            }
        }
        while ( $limit > $offset );

        return $output;
    }

    /**
     * Função para redimensionar imagens
     * @param ($_FILE) $tmp_file - Arquivo que veio no post do form
     * @param (string) $dest_img - Destino da imagem
     * @param (int) $maxW - Largura máxima
     * @param (int) $maxH - Altura máxima
     * @return (bool) - Sucesso
     */
    public static function redimensionar_imagem($tmp_file, $dest_img, $maxW, $maxH, $keepRatio = true)
    {
        if ( !is_file($tmp_file['tmp_name']) )
        {
            vdie($tmp_file['tmp_name'].' não existe.');
        }

        if ( $keepRatio )
        {
            // Dimensões originais
            list( $width,
                  $height ) = getimagesize($tmp_file['tmp_name']);
            // Limites
            $max_width = ($maxW>0?$maxW:$width);
            $max_height = ($maxH>0?$maxH:$height);
            // Novas dimensões
            list ( $new_height,
                   $new_width ) = MY_Utils::calcular_novas_dimensoes($height, $width, $max_height, $max_width);
            $new_width = ($maxW > 0 ? $maxW : $max_width);
            $new_height = ($maxH > 0 ? $maxH : $max_heigth);
        }
        else
        {
            // Dimensões originais
            list( $width,
                  $height ) = getimagesize($tmp_file['tmp_name']);
            // Limites
            $max_width = ($maxW>0?$maxW:$width);
            $max_height = ($maxH>0?$maxH:$height);
            // Novas dimensões
            list ( $new_height,
                   $new_width ) = MY_Utils::calcular_novas_dimensoes_2($height, $width, $max_height, $max_width);
        }

        // Converte/redimensiona
        $tmp_img = $tmp_file['tmp_name'];
        if ( copy($tmp_img, $dest_img) && is_file($tmp_img) )
        {
            // Chama o arquivo com a classe WideImage
            require_once('WideImage/lib/WideImage.php');

            // Carrega a imagem a ser manipulada
            $image = WideImage::load($tmp_img);

            // Redimensiona a imagem
            $image = $image->resize($new_width, $new_height);
            if ( !$keepRatio )
            {
                // Corta a imagem
                $image = $image->crop(0, 0, $maxW, $maxH);
            }

            // JPG to progressive
            if ( array_pop(explode('.', $dest_img)) == 'jpg' )
            {
                imageinterlace($image->getHandle(), true);
            }

            // Salva a imagem em um arquivo (novo ou não)
            $image->saveToFile($dest_img);
        }
        else
        {
            vdie('Não foi possível gravar a imagem no servidor.');
        }

        // Se ok
        $ok = is_file($dest_img);
        if ( !$ok )
        {
            vdie('Não foi possível receber a imagem no servidor.');
        }

        return $ok;
    }

    /**
     * Obtém a extensão do arquivo a partir do MIME
     * @param @image Diretório de uma imagem
     * @return string $extensao
     */
    public function obter_extensao_imagem($image)
    {
        //$mime_type = image_type_to_mime_type(exif_imagetype($image['tmp_name']));

        // Default: jpg
        $extensao = 'jpg';
        /*if ( $mime_type == 'image/png' ) { $extensao = 'png'; }
        if ( $mime_type == 'image/gif' ) { $extensao = 'gif'; }
        if ( $mime_type == 'image/bmp' ) { $extensao = 'bmp'; }
        if ( $mime_type == 'image/vnd.microsoft.icon' ) { $extensao = 'ico'; }*/

        return $extensao;
    }

    /**
     * .serialize() do jQuery retorna var1=valor1&var2=valor2&var3=valor3
     * Esta função retorna o seguinte array:
     * $array['var1'] = 'valor1';
     * $array['var2'] = 'valor2';
     * $array['var3'] = 'valor3';
     */
    function deserializar_dados_jquery($serializados)
    {
        $array_dados = array();

        // Organiza os dados
        foreach ( (array)explode('&', $serializados) as $dado )
        {
            $dado = explode('=', $dado);
            if ( strlen($dado[1]) > 0 )
            {
                $array_dados[$dado[0]] = utf8_decode(urldecode($dado[1]));
            }
        }

        return $array_dados;
    }

    /**
     * .serialize() do jQuery retorna var1=valor1&var2=valor2&var3=valor3
     * Esta função recebe o seguinte array:
     * $array['var1'] = 'valor1';
     * $array['var2'] = 'valor2';
     * $array['var3'] = 'valor3';
     *
     * E retorna var1=valor1&var2=valor2&var3=valor3.
     */
    function serializar_dados_jquery($array_dados)
    {
        $serializados = array();

        // Organiza os dados
        foreach ( $array_dados as $k => $dado )
        {
            $serializados[] = $k.'='.$dado;
        }

        return implode('&', $serializados);
    }

    /**
     * Função que recebe uma data e uma hora, e monta um timestamp: dd/mm/YYYY hh:mm
     * @param String $data
     * @param String $hora
     * @return String
     */
    function montar_timestamp($data, $hora)
    {
        if ( strlen($hora) != 5 )
        {
            $hora = '00:00';
        }

        // Monta os timestamps
        if ( (strlen($data) == 10) &&
             (strlen($hora) == 5) )
        {
            $data = $data.' '.$hora;
        }

        if ( strlen($data) != 16 )
        {
            $data = NULL;
        }

        return $data;
    }

    /**
     * Função que recebe um timestamp e retorna um array com hora e data separados.
     * @param String $data_hora
     * @param boolean $default_now - Boolean que informa que se a data estiver incorreta, considera como "hoje"
     * @return array - $timestamp['hora'] e $timestamp['data']
     */
    function quebrar_timestamp($data_hora, $default_now=true)
    {
        // Quebra o timestamp
        if ( strlen($data_hora) > 18 )
        {
            list( $timestamp['data'],
                  $timestamp['hora'] ) = explode(' ', $data_hora);
        }

        // Data
        if ( strlen($timestamp['data']) != 10 )
        {
            if ( $default_now )
            {
                $timestamp['data'] = date('Y-m-d');
            }
            else
            {
                $timestamp['data'] = '';
            }
        }
        // Hora
        if ( strlen($timestamp['hora']) < 8 )
        {
            if ( $default_now )
            {
                $timestamp['hora'] = date('H:i');
            }
            else
            {
                $timestamp['hora'] = '';
            }
        }
        else
        {
            $timestamp['hora'] = substr($timestamp['hora'], 0, 5);
        }

        return $timestamp;
    }

    /**
     * Função que verifica se o periodo é válido
     * @param String $dt_inicio
     * @param String $dt_fim
     * @return $dt_inicio <= AGORA && AGORA <= $dt_fim
     */
    function verificar_periodo_valido($dt_inicio, $dt_fim='')
    {
        $agora = date('Y-m-d H:i:59');
        if ( strlen($dt_fim) == 0 )
        {
            $dt_fim = $agora;
        }

        $ok = $dt_inicio <= $agora && $agora <= $dt_fim;

        return $ok;
    }

    /**
     * Função que converte data de YYYY-mm-dd hh-mm-ss para dd-mm-YYYY hh:mm:ss
     * @param String $data
     * @return String
     */
    function formata_data_hora($data)
    {
        // Quebra o timestamp
        $array_data = explode(' ', $data);

        $data_formatada = '';
        if ( count($array_data) == 2 )
        {
            $data_formatada = implode('/', array_reverse(explode('-', $array_data[0]))).' '.substr($array_data[1], 0, 5);
        }
        else
        {
            $data_formatada .= $data;
        }

        return $data_formatada;
    }

    function obter_dia_da_semana($data)
    {
        $dias_da_semana = array(
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado'
        );

        list(
            $ano,
            $mes,
            $dia
        ) = explode('-', $data);

        $dia_da_semana = date('w', mktime(0,0,0,$mes,$dia,$ano));

        return $dias_da_semana[$dia_da_semana];
    }

    /**
     * Verifica se um e-mail é válido
     * @param String $email
     * @return bool
     */
    function email_valido($email)
    {
        $email_valido = false;

        $conta = "^[a-zA-Z0-9\._-]+@";
        $domino = "[a-zA-Z0-9\._-]+.";
        $extensao = "([a-zA-Z]{2,4})$";
        $pattern = $conta.$domino.$extensao;
        $email_valido = (ereg($pattern, $email));

        return $email_valido;
    }

    function confere_codificacao($string)
    {
        if ( strlen(utf8_decode($string)) < strlen($string) )
        {
            $new_string = utf8_decode($string);
        }
        elseif ( strlen(utf8_encode($string)) < strlen($string) )
        {
            $new_string = utf8_encode($string);
        }
        else
        {
            $new_string = $string;
        }

        return $new_string;
    }

    /**
     * Identifica se o IP é interno (univates) ou externo
     *
     * @param (string) $ip - IP
     * @return (boolean) - Externo(t) ou Interno(f)
     */
    public static function ip_externo($ip)
    {
        /* IPs internos:
         *
         * 192.168.0.0
         * 192.168.3.255
         *
         * 192.168.100.0
         * 192.168.103.255
         *
         * 172.16.0.0
         * 172.16.255.255
         *
         * 172.17.0.0
         * 172.17.255.255
         *
         * 10.7.0.0
         * 10.7.3.255
         *
         * 10.8.0.0
         * 10.8.3.255
         *
         * 10.9.0.0
         * 10.9.3.255
         *
         * 10.3.0.0
         * 10.3.255.255
         *
         * 10.100.0.0
         * 10.100.255.255
         */
        $er = '/^(192\.168\.(10)?[0-3]|172\.1[67]\.[0-9]{1,3}|10\.(3|100)\.[0-9]{1,3}|10\.[789]\.[0-3])\.[0-9]{1,3}$/';
        if ( preg_match($er, $ip) )
        {
            $origem = 'interno';
        }
        else
        {
            $origem = 'externo';
        }
        file_put_contents(SERVERPATH.'/media/logs/ips_multimidia.log', date('Y-m-d H:i:s').' '.$ip.' - '.$origem."\n",FILE_APPEND);

        return $origem == 'externo';
    }
}

/**
 * Semelhante ao var_dump() do PHP, armazena o valor da variável em:
 * /tmp/var_dump.
 * Existe um script chamado flog.sh que lê em tempo real este arquivo e exibe em tela.
 *
 * @param: $1, $2, ..., $N: flog() pode receber quantos parametros forem necessarios.
 */
function flog()
{
    if ( file_exists('/tmp/flog_arthur') )
    {
        $numArgs = func_num_args();
        $dump = '';
        for($i = 0; $i < $numArgs; $i++)
        {
            $dump .= var_export(func_get_arg($i), true) . "\n";
        }

        $f = fopen('/tmp/flog_arthur', 'w');
        fwrite($f, $dump);
        fclose($f);
    }
}

/**
 * Semelhante ao var_dump() do PHP, mas com identacao. vd() coloca as tags <pre> do HTML
 * facilitando a visualizacao do valor contido na variavel. 
 *
 * @param: $1, $2, ..., $n: recebe N parametros.
 * Se o ultimo parametro for TRUE, executa exit()
 */
function vd()
{
    $numArgs = func_num_args();
    if ( $numArgs > 1 && is_bool(func_get_arg($numArgs - 1)) )
    {
        $numArgs--;
        $exit = func_get_arg($numArgs);
    }
    else
    {
        $exit = false;
    }

    echo '<div align="left"><pre>';
    for ( $i = 0; $i < $numArgs; $i++ )
    {
        var_dump(func_get_arg($i));
    }
    echo '</pre></div>';

    if ( $exit )
    {
        exit();
    }
}
/**
 * Semelhante ao var_dump() do PHP, mas com identacao. vd() coloca as tags <pre> do HTML
 * facilitando a visualizacao do valor contido na variavel. 
 *
 * @param: $1, $2, ..., $n: recebe N parametros.
 * Se o ultimo parametro for TRUE, executa exit()
 */
function vdie()
{
    $numArgs = func_num_args();
    if ( $numArgs > 1 && is_bool(func_get_arg($numArgs - 1)) )
    {
        $numArgs--;
        $exit = func_get_arg($numArgs);
    }
    else
    {
        $exit = false;
    }

    echo ('<div align="left"><pre>');
    for($i = 0; $i < $numArgs; $i++)
    {
        var_dump(func_get_arg($i));
    }
    echo ('</pre></div>');

    die();
}

function ultimo_sql($db)
{
    return array_pop($db->queries);
}

function inverter_data($timestamp)
{
    // Trabalha a data/hora
    $data = current(explode(' ', $timestamp));
    $data = implode('/', array_reverse(explode('-', $data)));
    $hora = substr(array_pop(explode(' ', $timestamp)), 0, 5);

    return $data.' '.$hora;
}