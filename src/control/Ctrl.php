<?php
require_once("src/view/View.php");
require_once("src/model/Module.php");
require_once("src/model/ModuleStorage.php");
require_once("src/model/ModuleBuilder.php");

class Ctrl
{
    private $view;
    private $moduleStorage;

    public function __construct(View $view, ModuleStorage $moduleStorage)
    {
        $this->moduleStorage = $moduleStorage;
        $this->view = $view;
    }


    public function showInformation($id){
        if($this->moduleStorage->read($id)->getName()!= null) $this->view->makeModulePage($this->moduleStorage->read($id), $id);
        else $this->view->makeUnknownModulePage();
    }

    public function showPopulateDB(){
        $this->moduleStorage->generate();
        $this->view->makeListPage($this->moduleStorage->readAll());
    }

    public function showHomePage(){
        $this->view->makeHomePage();
    }

    public function showUnknownActionPage(){
        $this->view->makeUnknownActionPage();
    }

    public function showAboutPage(){
        $this->view->makeAboutPage();
    }

    public function showList(){
        $this->view->makeListPage($this->moduleStorage->readAll());
    }

}