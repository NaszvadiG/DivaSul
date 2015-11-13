<?php
// Título (inserir/editar)
$inserir_editar = 'Editar';
if ( is_null($enquete['id']) )
{
    $inserir_editar = 'Inserir';
}
?>
<h2>Enquete - <?=$inserir_editar;?></h2>

<?php
if ( !empty($erro) )
{
    echo '<div class="msg erro">'.$erro.'</div>';
}
?>

<?php echo form_open()); ?>
<table class="form">
    <tbody>
<?php
if ( $enquete['id'] )
{
?>
        <tr>
            <th>Código</th>
            <td>
                <?php echo $enquete['id']; ?>
                <input type="hidden" name="enquete[id]" value="<?php echo $enquete['id']; ?>"/>
            </td>
        </tr>
<?php
}
?>

        <tr>
            <th>Título</th>
            <td>
                <?=form_input('enquete[titulo]', $enquete['titulo'], 'size="60"');?>
            </td>
        </tr>

        <tr>
            <th>Descrição</th>
            <td>
<?php
$campo_descricao = array(
    'cols' => 98,
    'rows' => 8,
    'id' => 'descricao',
    'name' => 'enquete[descricao]',
    'class' => 'ckeditor',
    'value' => $enquete['descricao']
);

echo form_textarea($campo_descricao);
?>
            </td>
        </tr>

        <tr>
            <th>Resposta</th>
            <td>
<?php
require_once('form_enquete_respostas.php');
?>
                <br />
                <div style="clear:both;"></div>
                <br />
                Personalizar cores?
                <?php
                    $respostas_com_cores_personalizadas = strlen($respostas[0]['cor']) > 0;
                    echo form_checkbox('respostas_com_cores_personalizadas', 't', ($respostas_com_cores_personalizadas), 'id="respostas_com_cores_personalizadas"');
                ?>
                    Sim
            </td>
        </tr>

        <tr>
            <th>Permite múltipla resposta</th>
            <td>
<?php
echo form_checkbox('enquete[multipla_resposta]', 't', ($enquete['multipla_resposta'] == 't'), 'id="multipla_resposta"');
?>
            </td>
        </tr>

        <tr>
            <th>Limitar votação</th>
            <td>
                <?php echo form_input('enquete[intervalo_entre_votos]', $enquete['intervalo_entre_votos'], 'size="7"'); ?>
                <?php echo form_dropdown('multiplicar_tempo', array('1'=>'Segundos', '60'=>'Minutos', '3600'=>'Horas', '86400'=>'Dias'), '1', 'id="ativo"'); ?>
                <br />
                <small title="Limita por IP">Obs.: Só permite votar novamente após esse intervalo de tempo.</small>
            </td>
        </tr>

        <tr>
                <th>Data de início</th>
                <td>
<?php
if ( strlen($enquete['dt_inicio']) > 0 )
{
    $data_hora_inicio = MY_Utils::quebrar_timestamp($enquete['dt_inicio']);
    $enquete['dt_inicio'] = $data_hora_inicio['data'];
    $enquete['hora_inicio'] = $data_hora_inicio['hora'];
}
if ( strlen($enquete['dt_inicio']) == 0 && strlen($enquete['hora_inicio']) == 0 )
{
    $enquete['dt_inicio'] = date('Y-m-d');
    $enquete['hora_inicio'] = date('H:i');
}

if ( strlen($enquete['dt_fim']) > 0 )
{
    $data_hora_fim = MY_Utils::quebrar_timestamp($enquete['dt_fim']);
    $enquete['dt_fim'] = $data_hora_fim['data'];
    $enquete['hora_fim'] = $data_hora_fim['hora'];
}

$campo_data_inicio = array(
    'attr' => array(
        'id' => 'dt_inicio',
        'name' => 'enquete[dt_inicio]',
        'value' => $enquete['dt_inicio']
    )
);
$campo_hora_inicio = array(
    'attr' => array(
        'id' => 'hora_inicio',
        'name' => 'enquete[hora_inicio]',
        'value' => $enquete['hora_inicio']
    )
);
echo input_data_hora($campo_data_inicio, $campo_hora_inicio, true, 'campo data/hora início');
?>
                </td>
            </tr>

            <tr>
                <th>Data de fim</th>
                <td>
<?php
$campo_data_fim = array(
    'attr' => array(
        'id' => 'dt_fim',
        'name' => 'enquete[dt_fim]',
        'value' => $enquete['dt_fim']
    )
);
$campo_hora_fim = array(
    'attr' => array(
        'id' => 'hora_fim',
        'name' => 'enquete[hora_fim]',
        'value' => $enquete['hora_fim']
    )
);
echo input_data_hora($campo_data_fim, $campo_hora_fim, true, 'campo data/hora fim');
?>
                </td>
            </tr>

        <tr>
            <th>Ativo</th>
            <td>
<?php
echo form_dropdown('enquete[ativo]', array('t'=>'Sim', 'f'=>'Não'), $enquete['ativo'], 'id="ativo"');
?>
            </td>
        </tr>

        <tr class="limpo">
            <th></th>
            <td><?=form_submit('submit','Salvar','class="button ok"');?> <?=anchor('enquetes', 'Cancelar', 'class="button cancel"');?></td>
        </tr>
    </tbody>
</table>
<?php echo form_close(); ?>
<script type="text/JavaScript" language="JavaScript">
// Adiciona os calendários
$(function()
{
    $( "#dt_inicio" ).datepicker({
        showButtonPanel: true,
        dateFormat: "yy-mm-dd",
        dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
        dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthNamesShort: ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
        prevText : "Anterior",
        nextText : "Próximo",
        showAnim : "slideDown",
        // buttons
        showButtonPanel: false
    });
});

$(function()
{
    $( "#dt_fim" ).datepicker({
        showButtonPanel: true,
        dateFormat: "yy-mm-dd",
        dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
        dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
        monthNamesShort: ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
        lastText : "Anterior",
        nextText : "Próximo",
        showAnim : "slideDown",
        // buttons
        showButtonPanel: false
    });
});

$('#hora_inicio').timepicker(
{
    // Localization
    hourText: 'Hora',             // Define the locale text for "Hours"
    minuteText: 'Minuto',         // Define the locale text for "Minute"

    minutes: {
        starts: 0,                // First displayed minute
        ends: 59,                 // Last displayed minute
        interval: 5               // Interval of displayed minutes
    },
    rows: 4,                      // Number of rows for the input tables, minimum 2, makes more sense if you use multiple of 2
    showHours: true,              // Define if the hours section is displayed or not. Set to false to get a minute only dialog
    showMinutes: true,            // Define if the minutes section is displayed or not. Set to false to get an hour only dialog
    showAnim : "slideDown",

    // buttons
    showButtonPanel: true,
    showCloseButton: true,       // shows an OK button to confirm the edit
    closeButtonText: 'OK',      // Text for the confirmation button (ok button)
    showNowButton: true,         // Shows the 'now' button
    nowButtonText: 'Agora',         // Text for the now button
    showDeselectButton: false,    // Shows the deselect time button
    deselectButtonText: 'Deselecionar' // Text for the deselect button
});

$('#hora_fim').timepicker(
{
    // Localization
    hourText: 'Hora',             // Define the locale text for "Hours"
    minuteText: 'Minuto',         // Define the locale text for "Minute"

    minutes: {
        starts: 0,                // First displayed minute
        ends: 59,                 // Last displayed minute
        interval: 5               // Interval of displayed minutes
    },
    rows: 4,                      // Number of rows for the input tables, minimum 2, makes more sense if you use multiple of 2
    showHours: true,              // Define if the hours section is displayed or not. Set to false to get a minute only dialog
    showMinutes: true,            // Define if the minutes section is displayed or not. Set to false to get an hour only dialog
    showAnim : "slideDown",

    // buttons
    showButtonPanel: true,
    showCloseButton: true,       // shows an OK button to confirm the edit
    closeButtonText: 'OK',      // Text for the confirmation button (ok button)
    showNowButton: true,         // Shows the 'now' button
    nowButtonText: 'Agora',         // Text for the now button
    showDeselectButton: false,    // Shows the deselect time button
    deselectButtonText: 'Deselecionar' // Text for the deselect button
});
</script>
