<?php

ob_start();
?>

<h1>Page admin</h1>


<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
