<?php

ob_start();
?>

<h1>Page admin</h1>
<p>Email: <?=$_SESSION['ADMIN_EMAIL']?></p>
<p>ID: <?=$_SESSION['ADMIN_ID']?></p>
<p>Nom: <?=$_SESSION['ADMIN_FIRSTNAME']?></p>
<p>Prénom: <?=$_SESSION['ADMIN_LASTNAME']?></p>


<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
