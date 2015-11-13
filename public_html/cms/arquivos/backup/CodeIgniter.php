<?php
$directory = current(explode('www', dirname(__FILE__))).'/CodeIgniter'; //diretorio para compactar
$zipfile = 'CodeIgniter.zip'; // nome do zip gerado

// Array de arquivos
$filenames = array();

// Lê os arquivos
browse($directory);

// cria zip, adiciona arquivos...
$zip = new ZipArchive();
if ( $zip->open($zipfile, ZIPARCHIVE::CREATE) !== TRUE )
{
    exit("Não pode abrir: <$zipfile>\n");
}

foreach ($filenames as $filename)
{
    $file = $filename;
    $arquivo = substr($file, -3);
    $zip->addFile($filename,$filename);
}

echo 'Total de arquivos: <b>'.$zip->numFiles.'</b><br>';
echo '<br><a href="http://'.$_SERVER['SERVER_NAME'].'/cms/arquivos/backup/CodeIgniter.zip">Clique aqui para fazer o download</a>';
$zip->close();

function browse($dir)
{
    global $filenames;
    if ($handle = opendir($dir))
    {
        while (false !== ($file = readdir($handle)))
        {
            if ($file != "." && $file != ".." && is_file($dir.'/'.$file))
            {
                $filenames[] = $dir.'/'.$file;
            }
            else if ($file != "." && $file != ".." && is_dir($dir.'/'.$file))
            {
                browse($dir.'/'.$file);
            }
        }
        closedir($handle);
    }

    return $filenames;
}
?>
