<?php
//require 'pruebasii.php';
//exit();

require_once 'settings.php';
require_once "core/FrontController.php";

$sistema_sii = new FrontController();
$sistema_sii->start();
