<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
require_once dirname(__DIR__) . "/vendor/autoload.php";

function AddRow(){
    $title = (string)$_GET['title'];
    $desc = (string)$_GET['desc'];
    unset($_GET);
    if ($title != "" && $title != null && $desc != "" && $desc != null){
        if (strlen($title) > 25){
            echo("Ошибка, название должно иметь не больше 25 символов! ");
        }
        if (strlen($desc) > 200){
            echo("Ошибка, описание должно иметь не больше 200 символов! ");
        }
        $title = '"' . $title . '"';
        $desc = '"' . $desc . '"';
        $delchars = ['<','>'];
        $title = str_replace($delchars, '', $title);
        $desc = str_replace($delchars, '', $desc);
        $db = new PDO("mysql:host=localhost;dbname=lr7", "lr7user", "lr7parol");
        $query = $db->prepare("insert into lr7.data (title, description) values ($title,$desc)");
        $query->execute();
    }
}

AddRow();
try {
    $twig = new Environment(new FilesystemLoader(dirname(__DIR__) . "/src/templates"));
    $db = new PDO("mysql:host=localhost;dbname=lr7", "lr7user", "lr7parol");
    $query = $db->prepare("select * from lr7.data");
    $query->execute();
    $data = $query->fetchAll();
    echo $twig->render("index.html.twig", array('data' => $data));
}
catch (Exception $e){
    echo ("Ошибка, сервер не может загрузить страницу. Попробуйте позже.");
}
