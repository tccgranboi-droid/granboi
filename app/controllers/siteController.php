<?php
require_once "Controller.php";
class siteController extends Controller
{
public function somosNos()
{
$this->render("site/somosNos");
}
public function contato()
{
$this->render("site/contato");
}
}
?>