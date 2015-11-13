<?php
function generate_url($title)
{
    return url_title(convert_accented_characters($title), '_', TRUE);
}
?>
