<?php
define("WEBROOT","http://localhost:8000/");
define("ROOT", str_replace("public","",$_SERVER['DOCUMENT_ROOT']));

require_once ROOT."config/helper.php";
require_once ROOT. "routes/router.php"; 
