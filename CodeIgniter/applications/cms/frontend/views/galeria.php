<?php
if ( is_array($fotos) && count($fotos) > 0 )
{
    natsort($fotos);
?>
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title">Fotos da inauguração</h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<div id="links">
<?php
    foreach ( $fotos as $foto )
    {
        $thumb = basename($foto);
        $img = str_replace('thumb_', '', basename($thumb));
?>
    <a href="<?php echo $url.$img; ?>"><img src="<?php echo $url.'/'.$thumb; ?>" alt="<?php echo $img; ?>" class="miniatura" border="0"></a>
<?php
    }
?>
</div>
<?php
}
else
{
?>
    <div class="msg erro"><?php echo $erro; ?></div>
<?php
}
?>
