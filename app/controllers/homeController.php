<?php
require_once "Controller.php";
class HomeController extends Controller
{
public function homeGranboi()
{
$this->render("home/homeGranboi", [
"titulo" => "GRANBOI"
]);

}
}
?>