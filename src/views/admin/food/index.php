<?php

ob_start();
?>

<h1>Plats Pti' Riad</h1>


<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
