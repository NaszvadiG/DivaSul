<h2>Files - editar</h2>
<?php
if ( !empty($erro) )
{
    echo '<div class="msg erro">'.$erro.'</div>';
}
?>

<?php echo form_open_multipart(); ?>
<table class="form">
    <tbody>
        <tr>
            <th>Nome Arquivo</th>
            <td>
                <input type="hidden" name="name" value="<?php echo $name; ?>"><?php echo array_pop(explode('/', $name)); ?></td>
        </tr>
        <tr>
            <th>Conteudo</th>
            <td>
                <textarea id="editor_codigo" name="content" style="height:500px;width:500px;"><?php echo $content; ?></textarea>
            </td>
        </tr>
        <tr class="limpo">
            <th></th>
            <td>
                <input class="btn btn-success" type="submit" name='file' value="Salvar" id="confirm"/>
                <input class="btn btn-danger" type="button" value="Cancelar" onclick="history.back(-1)">
            </td>
        </tr>
    </tbody>
</table>
<?php echo form_close(); ?>
