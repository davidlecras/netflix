<?php
//On initialise la SESSION: session_start();
// On désactive la SESSION: session_unset();
// On dettruit la SESSION: session_destroy();

session_start();
session_unset();
session_destroy();

header('location: index.php');
exit;
