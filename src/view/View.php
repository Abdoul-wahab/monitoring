<?php
 require_once("src/model/Module.php");
 require_once("src/model/ModuleBuilder.php");
 require_once("src/Router.php");

class View{
	private $title;
	private $content;
	private $router;

	protected $mModule;

	public function __construct(Router $router){
		$this->router = $router;
		$this->title = null;
		$this->content = null;
	}

    /**
     * @return null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param null $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param null $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

	// Page final
	public function render(){

		if($this->title === null || $this->content === null)
			 $this->makeUnexpectedErrorPage();
		$content = $this->content;
		$title = $this->title;

		$menu = array(
			"Accueil" => $this->router->getHomeURL(),
			"Liste des modules" => $this->router->getListURL(),
			"Ajouter un module" => $this->router->getModuleCreate(),
            "Générer des modules" => $this->router->getGeneratetURL(),
            "A Propos" => $this->router->getAboutURL(),
		);
		include("tamplete.php");
	}

	//Vue de la page d'acceuil'
	public function makeHomePage(){
		$this->title = "Bienvenue";
		$this->content = "Ceci est un site web de monitoring de modules IOT";

	}

	// l'Objet passé dans l'url est inconnu
	public function makeUnknownModulePage(){
			$this->title = "Erreur";
			$this->content = "Module Inconnu";
		}
		//Vue de la Page A propos
    public function makeAboutPage(){
  			$this->title = "Quelques détails ";
  			$this->content = "
            <p>L'option <strong><em>Générer des module</em></strong> permet de générer 100 modules à la fois.</p>
            <p>Si vous rémarquez qu'à chaque fois que vous rechargez la page, les les objets générés disparaissent, ce que vous avez peut-être oublié de commenter les lignes   <strong>\$initDB= new InitDB();</strong>   et   <strong>\$db->query(\$initDB->getSql())->execute();  </strong> 
            du fichier <strong>monitoring/index.php</strong>, ces lignes servent à créer une table initialisée avec un prémier objet dans la  base de données indiquée dans le fichier <strong>monitoring/src/config/config.php</strong>, du coup n'hésitez pas a aller jeter un coup d'oil.</p>
            <p>Pour réinitialiser la base, vous n'avez qu'à les décommenter et les recommenter plus tard</p>
            <p>Les images des modules générés ne s'affichent pas, ne faites pas attention à cela, car je crois que c'est un ptit bug de Ficker (l'outil que j'ai utilisé pour générer des fausses informations)</p> 
            <p><strong>Technologies Utilisées :</strong> HTML/CSS/BOOTSTRAP/PHP/MYSQL et L'architecture MVC</p>
            ";
  		}

	//Vue des Détails d'un objet
	public function makeModulePage(Module $module, $id){
        $this->title = $module->getName();
        $this->content ="";
        if((bool)rand(0,1)) $this->content .= $this->alert("capteur de température");
        if((bool)rand(0,1)) $this->content .= $this->alert("permettant de définir la durée de fonctionnement");
        if((bool)rand(0,1)) $this->content .= $this->alert("définissant le nombre de données");
        if((bool)rand(0,1)) $this->content .= $this->alert("qui traite l'état de marche de ".$module->getName());

        $this->content .='
        <table class="table table-hover">
          <thead>
            
          </thead>
          <tbody>
            <tr>
              <td colspan="2">Nom </td>
              <td>@' .$module->getName().'</td>
            </tr>
            <tr>
              <td colspan="2">Numéro</td>
              <td>@'.$module->getNumber().'</td>
            </tr>
            <tr>
              <td colspan="2">Type</td>
              <td>@'.$module->getType().'</td>
            </tr>
            <tr>
              <td colspan="2">Description</td>
              <td>@'.$module->getDescription().'</td>
            </tr>
            
            <tr>
              <td colspan="2">Temperature </td>
              <td>@' .$module->getTemperature().'</td>
            </tr>
            <tr>
              <td colspan="2">Durée de fonctionnement</td>
              <td>@'.$module->getTime().'</td>
            </tr>
            <tr>
              <td colspan="2">Nombre de données</td>
              <td>@'.$module->getNombre().'</td>
            </tr>
            <tr>
              <td colspan="2">Etat de marche</td>
              <td>@'.$module->getState().'</td>
            </tr>
          </tbody>
        </table>';

        $this->content .= "<p><a href=".$this->router->getModuleUpdateURL($id)."><button type='button'>Modifier !</button></a>";
        $this->content .= "<a href=".$this->router->getModuleAskDeletionURL($id)."><button type='button'>Supprimer !</button></a>";
        $this->content .= '<a href="'.$this->router->getModuleURL($id).'" ><button type=\'button\'>Générer un état !</button></a></p>';
		$this->content .= "

        <div class=\"modulePage\">
		    <img src='".$this->router->getImagesURL()."".$module->getImage()."' alt=\"Image\" width=\"200px\" height=\"300px\">
        </div>
        
		<div aria-live=\"polite\" aria-atomic=\"true\" class=\"d-flex justify-content-center align-items-center\" style=\"min-height: 200px;\">

		  <!-- Then put toasts within -->
		  <div class=\"toast\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\">
			<div class=\"toast-header\">
			  <img src=\"...\" class=\"rounded mr-2\" alt=\"...\">
			  <strong class=\"mr-auto\">Bootstrap</strong>
			  <small>11 mins ago</small>
			  <button type=\"button\" class=\"ml-2 mb-1 close\" data-dismiss=\"toast\" aria-label=\"Close\">
				<span aria-hidden=\"true\">&times;</span>
			  </button>
			</div>
			<div class=\"toast-body\">
			  Hello, world! This is a toast message.
			</div>
		  </div>
		</div>	
		
";

	}

	//Vue de la Liste des objets
	public function makeListPage($modules){
		$this->title = "liste";
		$this->content = "";
        foreach ($modules as $key => $module){
            $this->content .= "
            <p style=\"margin-left: 40px\">
				<a href='".$this->router->getModuleURL($key)."'>
					<span style='font-size: 20px; margin-left: 20px'>
						".$module->getName()."
					</span>
				</a>
            </p>
            ";
        }

	}
	//Page d'erreurs
	public function makeUnexpectedErrorPage() {
		$this->title = "Erreur";
		$this->content = "Une erreur inattendue s'est produite.";
	}
	//Url inconnue
  public function makeUnknownActionPage() {
		$this->title = "Erreur";
		$this->content = "La page demandée n'existe pas.";
	}

// Vue du formulaire de Création d'un Objet
  public function makeModuleCreationPage($data, $build){
      $this->title = 'Ajout de module';
      $this->content="
        <form action='".$this->router->getModuleSaveURL()."' method='post' enctype=\"multipart/form-data\" class='form-creation'>
        
        <div>
			<p><label for='".NAME_REF."'>Nom: </label>
			<input class='form-control form-control-sm' type='text' name='".NAME_REF."' id='".NAME_REF."' value='".$data[NAME_REF]."'>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[NAME_REF]."
			</div></p>
			
			<p><label for='".NUMBER_REF."'>Numéro: </label>
			<input class='form-control form-control-sm' type='number' name='".NUMBER_REF."' id='".NUMBER_REF."' value='".$data[NUMBER_REF]."'>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[NUMBER_REF]."
			</div></p>
			
			<p><label for='".TYPE_REF."'>Type: </label>
			<input class='form-control form-control-sm' type='text' name='".TYPE_REF."' id='".TYPE_REF."' value='".$data[TYPE_REF]."'>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[TYPE_REF]."
			</div></p>
			
			<p><label for='".DESCRIPTION_REF."'>Description: </label>
			<textarea class='form-control form-control-sm' class='text-area' name='".DESCRIPTION_REF."' value='".$data[DESCRIPTION_REF]."'>".$data[DESCRIPTION_REF]."</textarea>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[DESCRIPTION_REF]."
			</div></p>
			
			<p><label for='".TEMPERATURE_REF."' >Température max: </label>
			<select name='".TEMPERATURE_REF."' class=\"form-control form-control-sm\">
			  <option>-10°C à 10°C</option>
			  <option>11°C à 20°C</option>
			  <option>21°C à 35°C</option>
			  <option>36°C à 50°C</option>
			  <option>Plus de 51°C</option>
			</select></p>
			
			<p><label for='".TIME_REF."'>Durée de fonctionnement: </label>
			<select name='".TIME_REF."' class=\"form-control form-control-sm\">
			  <option>6 mois à 1 ans</option>
			  <option>1 à 3 ans</option>
			  <option>3 à 5 ans</option>
			  <option>5 à 10 ans</option>
			  <option>Plus de 10 ans</option>
			</select></p>
			
			<p><label for='".NOMBRE_REF."'>Nombre de données envoyées: </label>
			<select name='".NOMBRE_REF."' class=\"form-control form-control-sm\">
			  <option>Large select 1</option>
			  <option>Large select 2</option>
			  <option>Large select 3</option>
			  <option>Large select 4</option>
			  <option>Large select 5</option>
			</select></p>
			
			<p><label for='".STATE_REF."'>Etat de marche: </label>
			<select name='".STATE_REF."' class=\"form-control form-control-sm\">
			  <option>Large select 1</option>
			  <option>Large select 2</option>
			  <option>Large select 3</option>
			  <option>Large select 4</option>
			  <option>Large select 5</option>
			</select></p>
			
			
			<p><label for='".IMAGE_REF."'>Image: </label>
			<input type='hidden' name='".IMAGE_REF."' id='".IMAGE_REF."' value='".$data[IMAGE_REF]. "'>
			<input type='file' name='".IMAGE_REF."' id='".IMAGE_REF."' value='".$data[IMAGE_REF]. "'>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[IMAGE_REF]."
			</div></p>
			
			<input type='submit' value='Ajouter'>
        </div>
        
        </form>
        ";
  }

  // Vue du formulaire prérempli des de mise à jour d'un objet
  public function makeModuleUpdatePage($data, $build, $id){

      $this->title = 'Modification du module';
      $this->content="
        <form action='".$this->router->getModuleUpdateURL($id)."' method='post' enctype=\"multipart/form-data\" class='form-creation'>
        
        <div class='fr'>
        	<p>
			<label for='nom'>Nom: </label>
			<input class='form-control form-control-sm' type='text' name='".NAME_REF."' id='".NAME_REF."' value='".$data[NAME_REF]."'>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[NAME_REF]."
			</div></p>
			
			<p><label for='nom'>Type: </label>
			<input class='form-control form-control-sm' type='text' name='".TYPE_REF."' id='".TYPE_REF."' value='".$data[TYPE_REF]."'>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[TYPE_REF]."
			</div></p>
			
			<p><label for='nom'>Numéro: </label>
			<input class='form-control form-control-sm' type='number' name='".NUMBER_REF."' id='".NUMBER_REF."' value='".$data[NUMBER_REF]."'>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[NUMBER_REF]."
			</div></p>
			
			<p>
			<label for='".DESCRIPTION_REF."'>Description: </label>
			<textarea class='form-control form-control-sm' name='".DESCRIPTION_REF."'>".$data[DESCRIPTION_REF]."</textarea>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[DESCRIPTION_REF]."
			</div></p>
			
			<p><label for='".TEMPERATURE_REF."' >Température max: </label>
			<select name='".TEMPERATURE_REF."' class=\"form-control form-control-sm\" value='".$data[TEMPERATURE_REF]."'>
			  <option>-10°C à 10°C</option>
			  <option>11°C à 20°C</option>
			  <option>21°C à 35°C</option>
			  <option>36°C à 50°C</option>
			  <option>Plus de 51°C</option>
			</select></p>
			
			<p><label for='".TIME_REF."'>Durée de fonctionnement: </label>
			<select name='".TIME_REF."' class=\"form-control form-control-sm\" value='".$data[TIME_REF]."'>
			  <option>6 mois à 1 ans</option>
			  <option>1 à 3 ans</option>
			  <option>3 à 5 ans</option>
			  <option>5 à 10 ans</option>
			  <option>Plus de 10 ans</option>
			</select></p>
			
			<p><label for='".NOMBRE_REF."'>Nombre de données envoyées: </label>
			<select name='".NOMBRE_REF."' class=\"form-control form-control-sm\" value='".$data[NOMBRE_REF]."'>
			  <option>Large select 1</option>
			  <option>Large select 2</option>
			  <option>Large select 3</option>
			  <option>Large select 4</option>
			  <option>Large select 5</option>
			</select></p>
			
			<p><label for='".STATE_REF."'>Etat de marche: </label>
			<select name='".STATE_REF."' class=\"form-control form-control-sm\" value='".$data[STATE_REF]."'>
			  <option>Large select 1</option>
			  <option>Large select 2</option>
			  <option>Large select 3</option>
			  <option>Large select 4</option>
			  <option>Large select 5</option>
			</select></p>
			
			<p>
			<label for='".IMAGE_REF."'>Image: </label>
			<input type='hidden' name='".IMAGE_REF."' id='".IMAGE_REF."' value='".$data[IMAGE_REF]. "'>
			<input type='file' name='".IMAGE_REF."' id='".IMAGE_REF."' value='".$data[IMAGE_REF]. "'>
			<div class='form-fields form-error' style='color: #ff4c1e'>
			".$build->getDataError()[IMAGE_REF]."
			</div></p>
			
			<input type='submit' value='modifier'>
        </div>
        </form>
        ";
  }

  //Vue de confirmation de suppression d'un module
  public function makeModuleAskDeletionPage($module, $id){
      $this->title= "Page confirmation de suppression";
      $this->content= "
        <p>Vous êtes sur de vouloir supprimer ce module ? <a href='".$this->router->getModuleURL($id)."'>".$module->getName()."</a></p>
        <p><a href='".$this->router->getModuleDeletionURL($id)."'><button type='button'>oui</button></a><a href='".$this->router->getModuleURL($id)."'><button type='button'>non</button></a> </p>
        ";
  }

  // Suppréssion d'un objet de la bd
  public function makeModuleDeletedPage() {
		$this->title = "Suppression effectuée";
		$this->content = "<p>Le module a été correctement supprimée.</p>";
	}
    public function alert($data)
    {
        if ($data != "") {
            return "
                <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                  <h2 class='alert-heading'>Alert</h2>
                  <strong>Attention la variable " . $data . " ne fonctionne pas !!!</strong>
                  <button type=\"button\" class=\"close\" data-dismiss=\"alert\" >
                    <span aria-hidden=\"true\">&times;</span>
                  </button>
                </div>
                ";
        }
    }
}

?>
