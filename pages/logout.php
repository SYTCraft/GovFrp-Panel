<?php
namespace SYTCraftPanel;

use SYTCraftPanel;

SYTCraftPanel\Utils::checkCsrf();

unset($_SESSION['user']);
unset($_SESSION['mail']);
unset($_SESSION['token']);
?>
<script>location='/';</script>