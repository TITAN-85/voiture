<?php
require 'app/includes/config.php';
require 'app/includes/chargementClasses.inc.php';
session_start();
(new Routeur)->router();


// $ps->debugDumpParams();
// die();