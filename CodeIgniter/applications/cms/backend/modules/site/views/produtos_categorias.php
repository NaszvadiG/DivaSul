<?php
// Título (inserir/editar)
$inserir_editar = 'Inserir';
if ( strlen($tipo_de_produto['id']) > 0 )
{
    $inserir_editar = 'Editar';
}
?>

<h2>Tipo de serviço - <?php echo $inserir_editar;?></h2>

<?php
// Mensagens:
if ( is_array($erros) && count($erros) > 0 )
{
    $erro = implode('<br>', $erros);
}
if ( strlen($erro) > 0 )
{

    echo '<div class="msg error">'.$erro.'</div>';
}
// Fim das mensagens
?>

<?php echo form_open_multipart('site/produtos/editar_categoria/'.$categoria['id']); ?>
<input type="hidden" name="categoria[id]" value="<?php echo $categoria['id'];?>" />
<table width="100%" border="0">
<?php
if ( strlen($categoria['id']) > 0 )
{
?>
    <tr>
        <th align="right">Código:</th>
        <td><?php echo $categoria['id'];?></td>
    </tr>
<?php
}
?>

        <tr>
            <th>Categoria Pai</th>
            <td>
<?php
$opcoes = array();
$opcoes[0] = '-- Selecione --';
foreach ( (array)$categorias as $cat )
{
    $opcoes[$cat['id']] = $cat['titulo'];
}
?>
                <?php echo form_dropdown('categoria[parent_id]', $opcoes, $categoria['parent_id']);?>
            </td>
        </tr>

    <tr>
        <th align="right">Título:</th>
        <td>
<?php
$data = array(
    'name' => 'categoria[titulo]',
    'id' => 'titulo',
    'value' => $categoria['titulo'],
    'size' => '60',
    'title' => 'Informe o título da categoria'
);
?>
            <?php echo form_input($data);?>
        </td>
    </tr>

<?php
/*
    <tr>
        <th align="right">Link:</th>
        <td>
<?php
$data = array(
    'name' => 'categoria[link]',
    'id' => 'link',
    'value' => $categoria['link'],
    'size' => '60',
    'title' => 'Informe o link da categoria'
);
?>
            <?php echo form_input($data);?>
        </td>
    </tr>
 */
?>
    <tr>
        <th align="right">Descrição:</th>
        <td>
<?php
$input_data = array(
    'name' => 'categoria[descricao]',
    'id' => 'descricao',
    'value' => $categoria['descricao'],
    'size' => '170',
    'rows' => '9',
    'title' => 'Informe a descrição da categoria',
    'class' => 'ckeditor'
);
?>
            <?php echo form_textarea($input_data);?>
        </td>
    </tr>

    <tr>
        <th align="right">Ícone:</th>
        <td>
<?php
if ( strlen($categoria['id']) > 0 )
{
    $imagem = current((array)glob(SERVERPATH.'arquivos/tipos-produtos/'.$categoria['id']."/{icone_*.*}", GLOB_BRACE));
    if ( is_file($imagem) )
    {
        $imagem = basename($imagem);
        $url = base_url('../arquivos/tipos-produtos/'.$categoria['id'].'/'.$imagem);
?>
    Imagem atual:<br />
    <img src="<?php echo $url; ?>" border="0"/><br /><br />
<?php
    }
}
$data = array(
    'name' => 'icone',
    'id' => 'icone',
    'size' => '60',
    'title' => 'Envia um icone para esta categoria'
);
?>
            <?php echo form_upload($data);?>
        </td>
    </tr>

    <tr>
        <th align="right">Imagem:</th>
        <td>
<?php
if ( strlen($categoria['id']) > 0 )
{
    $imagem = current((array)glob(SERVERPATH.'arquivos/tipos-produtos/'.$categoria['id']."/{imagem_*.*}", GLOB_BRACE));
    if ( is_file($imagem) )
    {
        $imagem = basename($imagem);
        $url = base_url('../arquivos/tipos-produtos/'.$categoria['id'].'/'.$imagem);
?>
    Imagem atual:<br />
    <img src="<?php echo $url; ?>" border="0"/><br /><br />
<?php
    }
}
$data = array(
    'name' => 'imagem',
    'id' => 'imagem',
    'size' => '60',
    'title' => 'Envia uma imagem "capa" desta categoria'
);
?>
            <?php echo form_upload($data);?>
        </td>
    </tr>

    <tr>
        <th>Ativo</th>
        <td>
<?php
// Campo ativo
$options = array(
    1 => 'Sim',
    0 => 'Não'
);
?>
            <?php echo form_dropdown('categoria[ativo]', $options, $categoria['ativo']);?>
        </td>
    </tr>

    <tr class="limpo">
        <th>&nbsp;</th>
        <td>
            <?php echo form_submit('submit', 'Salvar', 'class="button ok"');?>
            <?php echo anchor('site/produtos/listar_categorias', 'Cancelar', 'class="button cancel"');?>
        </td>
    </tr>
</table>
<?php echo form_close(); ?>
