<?php
/**
 * Este arquivo tem por objetivo mover as imagens recebidas no $_FILES para o destino
 * recebido no $_GET. É chamado pelo Uploadify das galerias (CodeIgniter>>backend)
 */
$ok = false;
$dest = urldecode(base64_decode($_GET['path']));
$file = $_FILES['Filedata'];
if ( is_array($file) && count($file) > 0 )
{
    if ( move_uploaded_file($file['tmp_name'], $dest.removeSpecialChars(strtolower($file['name']))) )
    {
        $ok = true;
    }
}

echo $ok;

/**
 * Script para remover acentos e caracteres especiais:
 */
function removeSpecialChars($oldText)
{
    // Se corrige os acentos com iso, taca iso
    if ( strlen($oldText) > strlen(utf8_decode($oldText)) )
    {
        $oldText = utf8_decode($oldText);
    }

    /*
     * A função "strtr" substitui os caracteres acentuados pelos não acentuados.
     * A função "ereg_replace" utiliza uma expressão regular que remove todos os
     * caracteres que não são letras, números e são diferentes de "_" (underscore).
     */
    $newText = preg_replace('[^a-zA-Z0-9_-.]', '', strtr($oldText, 'áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ', 'aaaaeeiooouucAAAAEEIOOOUUC_'));

    if ( !(strlen($newText) > 0) )
    {
        $newText = 'nome_invalido-'.getRandomNumbers().getRandomNumbers();
    }

    return $newText;
}

/**
 * Get 6 random numbers
 */
function getRandomNumbers()
{
    return rand(100,999).rand(100,999);
}
?>

<?php
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
?>
