<?php

ob_start();
?>

Le pti' Riad


<?php

$content = ob_get_clean();

require_once('../src/views/layout.php');