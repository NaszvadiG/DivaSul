<style type="text/css">
div#imagens_existentes div.caixa_imagem,
div#ajax_imagens div.caixa_imagem
{
    float:left;
    padding:5px;
}
div#imagens_existentes div.caixa_imagem input[type='text'],
div#ajax_imagens div.caixa_imagem input[type='text']
{
    width:196px;
}
div#imagens_existentes div.imagem,
div#ajax_imagens div.imagem
{
    width:200px;
    height:133px;
    -o-box-shadow:3px 3px 6px #777;
    -moz-box-shadow:3px 3px 6px #777;
    -webkit-box-shadow:3px 3px 6px #777;
    box-shadow:3px 3px 6px #777;
    background-size:cover;
    background-repeat:no-repeat;
    background-position:center;
}
div#imagens_existentes div img.remover_imagem,
div#ajax_imagens div img.remover_imagem
{
    float:right;
    cursor:pointer;
    padding: 3px 3px 4px 5px;
    background-image: url('<?php echo base_url('../arquivos/imagens/black_30.png'); ?>');
    -0-border-radius: 0px 0px 0px 10px;
    -moz-border-radius: 0px 0px 0px 10px;
    -webkit-border-radius: 0px 0px 0px 10px;
    border-radius: 0px 0px 0px 10px;
    opacity:0.75;
}
div#imagens_existentes div img.remover_imagem:hover,
div#ajax_imagens div img.remover_imagem:hover
{
    opacity:1;
}
div#imagens_existentes div.caixa_imagem input[type='checkbox'].capa,
div#ajax_imagens div.caixa_imagem input[type='checkbox'].capa
{
    margin:5px 5px 7px 1px;
}
</style>
