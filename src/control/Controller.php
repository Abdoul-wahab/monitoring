<?php
require_once("src/view/View.php");
require_once("src/model/Module.php");
require_once("src/model/ModuleStorage.php");
require_once("src/model/ModuleBuilder.php");

class Controller
{
	private $view;
	private $moduleStorage;

	public function __construct(View $view, ModuleStorage $moduleStorage)
	{
		$this->moduleStorage = $moduleStorage;
		$this->view = $view;
	}

    // controle de l'affichage des détails d'un module
	public function showInformation($id){
		if($this->moduleStorage->read($id)->getName()!= null) $this->view->makeModulePage($this->moduleStorage->read($id), $id);
		else $this->view->makeUnknownModulePage();
	}

	//generer 100 modules
	public function showPopulateDB(){
		$this->moduleStorage->generate();
		$this->view->makeListPage($this->moduleStorage->readAll());
	}

	//commande de la page d'accueil
	public function showHomePage(){
		$this->view->makeHomePage();
	}

	//commande de la page d'url introuvée
	public function showUnknownActionPage(){
		$this->view->makeUnknownActionPage();
	}

	//Commande de la page a propo
	public function showAboutPage(){
		$this->view->makeAboutPage();
	}

	//commande de la page de la liste
	public function showList(){
		$this->view->makeListPage($this->moduleStorage->readAll());
	}

    //control de la  confirmation de suppression d'un objet
    public function showModuleAskDeletion($id){
        if($this->moduleStorage->read($id)){
            $this->view->makeModuleAskDeletionPage($this->moduleStorage->read($id), $id);
        }else{
            $this->view->makeUnknownActionPage();
        }
    }

    //Control de la suppression d'un objet de la bd
    public function showModuleDeletion($id){
        $router= new Router();
        if($this->moduleStorage->read($id)){
            $this->moduleStorage->delete($id);
            header('Location: '.$router->getListURL());
        }else{
            $this->view->makeUnknownActionPage();
        }
    }


    //la fonction qui s'occupe d'enregistrer un nouveau module dans la bd
    public function showSaveNewModule(){
        if(key_exists(NAME_REF, $_POST) || key_exists(DESCRIPTION_REF, $_POST) || key_exists(IMAGE_REF, $_POST) || key_exists(IMAGE_REF, $_FILES) || key_exists(NUMBER_REF, $_POST) || key_exists(TYPE_REF, $_POST)){
            $data= array();
            $router= new Router();
            $data[NAME_REF]= key_exists(NAME_REF, $_POST)? $_POST[NAME_REF]: '';
            $data[IMAGE_REF]= (key_exists(IMAGE_REF, $_FILES))? $_FILES[IMAGE_REF]['name']: '';
            $data[DESCRIPTION_REF]= key_exists(DESCRIPTION_REF, $_POST)? $_POST[DESCRIPTION_REF]: '';
            $data[TYPE_REF]= key_exists(TYPE_REF, $_POST)? $_POST[TYPE_REF]: '';
            $data[NUMBER_REF]= key_exists(NUMBER_REF, $_POST)? $_POST[NUMBER_REF]: '';

            //Pas besoin de vérifier
            $data[TEMPERATURE_REF] = $_POST[TEMPERATURE_REF];
            $data[TIME_REF] = $_POST[TIME_REF];
            $data[NOMBRE_REF] = $_POST[NOMBRE_REF];
            $data[STATE_REF] = $_POST[STATE_REF];


            $moduleBuilder = new ModuleBuilder($data);
            if($moduleBuilder->isValid()){

                $module= $moduleBuilder->createModule();
                if($this->uploadImage($_FILES[IMAGE_REF])){
                    $this->moduleStorage->create($module);
                    // Rédirection ves la page des détails
                    header('Location: '.$router->getModuleURL($this->getId($data)));
                }
                else{
                    $this->view->makeModuleCreationPage($data, $moduleBuilder);
                }

            }
            else{
                $this->view->makeModuleCreationPage($data, $moduleBuilder);
            }
        }
        else{
        }

    }


    //Control des Modilficaton des information d'un module
    public function showUpdateModule($id){
        $router= new Router();
        if(key_exists('name', $_POST) || key_exists('number', $_POST) || key_exists('description', $_POST) ||  key_exists('image', $_POST) || key_exists('type', $_POST)){
            $data[NAME_REF]= key_exists(NAME_REF, $_POST)? $_POST[NAME_REF]: '';
            $data[IMAGE_REF]= key_exists(IMAGE_REF, $_POST)? $_POST[IMAGE_REF]: '';
            $data[NUMBER_REF]= key_exists(NUMBER_REF, $_POST)? $_POST[NUMBER_REF]: '';
            $data[DESCRIPTION_REF]= key_exists(DESCRIPTION_REF, $_POST)? $_POST[DESCRIPTION_REF]: '';
            $data[TYPE_REF]= key_exists(TYPE_REF, $_POST)? $_POST[TYPE_REF]: '';

            $data[TEMPERATURE_REF] = $_POST[TEMPERATURE_REF];
            $data[TIME_REF] = $_POST[TIME_REF];
            $data[NOMBRE_REF] = $_POST[NOMBRE_REF];
            $data[STATE_REF] = $_POST[STATE_REF];

            $moduleBuilder= new ModuleBuilder($data);
            if($moduleBuilder->isValid()){
                $module= $moduleBuilder->createModule();
                if(key_exists(IMAGE_REF, $_FILES) && $_FILES[IMAGE_REF]['name']!== ''){
                    $is_uploaded= $this->uploadImage($_FILES[IMAGE_REF]);
                    if ($is_uploaded=== true){
                        $module->setImage($_FILES[IMAGE_REF]['name']);
                    }
                }
                $this->moduleStorage->update($id, $module);
                header('Location: '.$router->getModuleURL($id));
            }
            else{
                $this->view->makeModuleUpdatePage($_POST, $moduleBuilder, $id);
            }
        }
        else{
            $module= $this->moduleStorage->read($id);
            $data= array();
            $data[NAME_REF]= $module->getName();
            $data[IMAGE_REF]= $module->getImage();
            $data[NUMBER_REF]= $module->getNumber();
            $data[DESCRIPTION_REF]= $module->getDescription();
            $data[TYPE_REF]= $module->getType();
            //Pas besoin de vérifier
            $data[TEMPERATURE_REF] = $module->getTemperature();
            $data[TIME_REF] = $module->getTime();
            $data[NOMBRE_REF] = $module->getNombre();
            $data[STATE_REF] = $module->getState();
            $moduleBuilder= new ModuleBuilder($data);
            $this->view->makeModuleUpdatePage($data, $moduleBuilder, $id);
        }

    }

    //Cette fonction permet de modifier aléatoirement l'état d'un d'un module IOT
    public function generateState(){
        $faker = Faker\Factory::create();

    }


    //telechargement d'une image sur le serveur
    public function uploadImage($image){
        $router= new Router();
        $uploadfile = $router->getImagesPath() . $image['name'];
        if (move_uploaded_file($image['tmp_name'], $uploadfile)) {
            return true;
        }
        return false;
    }

    //fonction de Vérification des informations(pour éviter les failles xss)
    public function noScript($value){
        $value= htmlentities($value);
        return $value;
    }

    //Récupération de l'id d'un module
    public function getId($data){
        $modules=$this->moduleStorage->readAll();
        foreach ($modules as $id => $module){
            if($data[NAME_REF] === $module->getName() and $data[NUMBER_REF] === $module->getNumber() and $data[TYPE_REF] === $module->getType()){
                return $id;
            }
        }
        return null;
    }

}