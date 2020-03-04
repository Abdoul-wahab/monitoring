<?php
 require_once("src/view/View.php");
 require_once("src/control/Controller.php");
 require_once("src/model/Module.php");
 require_once("src/model/ModuleStorage.php");

 class Router{
 	private $view;
 	private $ctrl;


 	public function main($moduleStorage){
	 	$this->view = new View($this);
	 	$this->ctrl = new Controller($this->view, $moduleStorage);

        /* Analyse de l'URL */
        $action = key_exists('action', $_GET)? $_GET['action']: null;
        $moduleID = key_exists('module', $_GET)? $_GET['module']: null;



        /* Pas d'action demandée : par défaut on affiche
        * la page d'accueil, sauf si un module est demandé,
        * auquel cas on affiche sa page. */
        if ($action === null) {
          $action = ($moduleID === null)? "accueil" : "pageBook";
        }

        /* Vérification de l'action démandée dans l'url */

        try{
          switch ($action) {
            case "pageBook":
              if($moduleID === null)
                $this->view->makeUnknownModulePage();
              else
                $this->ctrl->showInformation($moduleID);
              break;
            case "accueil":
              $this->ctrl->showHomePage();
              break;
            case "liste":
              $this->ctrl->showList();
              break;
            case "generer":
              $this->ctrl->showPopulateDB();
              break;

            case "supprimer":
                if($moduleID === null)
                    $this->view->makeUnknownModulePage();
                else
                    $this->ctrl->showModuleAskDeletion($moduleID);
              break;

           case "supprimerConfirmer":
                if($moduleID === null)
                  $this->view->makeUnknownModulePage();
                else
                  $this->ctrl->showModuleDeletion($moduleID);
            break;

              case "modifier":
                  if($moduleID === null)
                      $this->view->makeUnknownModulePage();
                  else
                      $this->ctrl->showUpdateModule($moduleID);
                  break;
              case "ajouter":
                  $data= array();
                  $data[NAME_REF]= '';
                  $data[DESCRIPTION_REF]= '';
                  $data[IMAGE_REF]= '';
                  $data[TYPE_REF]= '';
                  $data[NUMBER_REF]= 0;
                  $moduleBuilder= new ModuleBuilder($data);
                  $this->view->makeModuleCreationPage($data, $moduleBuilder);
                  break;
              case "save":
                  $this->ctrl->showSaveNewModule();
                  break;
            case "apropo":
              $this->view->makeAboutPage();
              break;
            default:
              $this->view->makeUnknownActionPage();
              break;
            }
        }
    catch (Exception $e) {
        /* Si on arrive ici, il s'est passé quelque chose d'imprévu
           * (par exemple un problème de base de données) */
        $this->view->makeUnexpectedErrorPage($e);
    }

    //La page final
		$this->view->render();
	}

    //Url de la page d'accueil
    public function getHomeURL(){
        return "?action=accueil";
    }

    // URL de la page de détails d'un module
    public function getModuleURL($id){
        return "?module=$id";
    }
     // Url de la page de l'ensemble des modules
      public function getListURL(){
        return "?action=liste";
      }

      // URL vers le formulaire de creation de nouveau module
      public function getModuleCreate(){
        return "?action=ajouter";
      }
      //url d'enrégistrement d'un module
      public function getModuleSaveURL(){
          return "?action=save";
      }
      //Url vers la page de modification d'un module
      public function getModuleUpdateURL($id){
        return "?module=$id&amp;action=modifier";
      }

      //url d'enrégistrement des modifications d'un module
      public function getSaveModuleUpdateURL($id){
        return "?module=$id&amp;action=saveModifs";
      }
      //Url de validation de la suppression d'un module
      public function getModuleAskDeletionURL($id){
        return "?module=$id&amp;action=supprimer";
      }

      //Url de la suppression
      public function getModuleDeletionURL($id){
        return "?module=$id&amp;action=supprimerConfirmer";
      }

      //Url de la page A propo
      public function getAboutURL(){
        return "?action=apropo";
      }
      //Url de génération de modules
      public function getGeneratetURL(){
            return "?action=generer";
      }

     public function getImagesURL(){
         return DIRECTORY_NAME."/src/images/";
     }
     public function getImagesPath(){
         return "src/images/";
     }
 }
?>
