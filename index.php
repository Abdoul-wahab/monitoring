<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire indique dans set_include_path.
 */
set_include_path("./monitoring");
require_once 'vendor/autoload.php';
set_include_path("./src");
/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");
require_once("src/model/ModuleStorageMySQL.php");
require_once("src/config/config.php");
require_once("src/config/InitDB.php");
/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */
$router = new Router();
try{
    $db = new PDO('mysql:host='.HOSTNAME.';port='.DATABASEPORT.';dbname='.DATABASE.';charset=utf8', USERDATABASE, PSWDDATABASE);
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}
$moduleStorage= new ModuleStorageMySQL($db);

/*
 * Les Deux Lignes juste après( $initDB= .......) sont à commenter après prémière exécution du code.
 * Ces lignes servent créer une tables initilisée avec un prémier module la dase de données.
 * Apres vous pouriez générer autant de modules que vous voudrier, décommenter ces lignes pour tous remettre à zéro */

$initDB= new InitDB();
$db->query($initDB->getSql())->execute();

//point d'entrer de l'appli(à ne pas commenter !!!)
$router->main($moduleStorage);


