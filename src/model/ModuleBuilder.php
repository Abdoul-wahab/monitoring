<?php

define("NAME_REF", "name");
define("IMAGE_REF", "image");
define("NUMBER_REF", "number");
define("DESCRIPTION_REF", "description");
define("TYPE_REF", "type");
define("TEMPERATURE_REF", "temperature");
define("TIME_REF", "time");
define("NOMBRE_REF", "nombre");
define("STATE_REF", "state");
class ModuleBuilder
{

    private $data= array(), $error= null, $data_error= array(NAME_REF=> null, IMAGE_REF=> null, NUMBER_REF=> null, DESCRIPTION_REF=> null, TYPE_REF=> null, TEMPERATURE_REF=> null, TIME_REF=> null, NOMBRE_REF=> null, STATE_REF=> null);

    /**
     * BookBuilder constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param null $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return array
     */
    public function getDataError()
    {
        return $this->data_error;
    }

    /**
     * @param array $data_error
     */
    public function setDataError(array $data_error)
    {
        $this->data_error = $data_error;
    }


//  creer un Module a partir de builder
    public function createModule(){
        $module= new Module($this->data[NAME_REF], $this->data[NUMBER_REF], $this->data[DESCRIPTION_REF], $this->data[TYPE_REF], $this->data[IMAGE_REF], $this->data[TEMPERATURE_REF], $this->data[TIME_REF], $this->data[NOMBRE_REF], $this->data[STATE_REF]);
        return $module;
    }

    public function isValid(){
        $name= key_exists(NAME_REF, $this->data)? $this->data[NAME_REF]: "";
        $image= key_exists(IMAGE_REF, $this->data)? $this->data[IMAGE_REF]: "";
        $type= key_exists(TYPE_REF, $this->data)? $this->data[TYPE_REF]: "";
        $number= key_exists(NUMBER_REF, $this->data)? $this->data[NUMBER_REF]: "";
        $description= key_exists(DESCRIPTION_REF, $this->data)? $this->data[DESCRIPTION_REF]: "";
        if($name!== "" && $image!== "" && $type!== "" && $number>= 0 && $description!== ""){
            return true;
        }
        else{
            $this->data_error[NAME_REF]= ($name=== "")? "Nom Invalide": null;
            $this->data_error[IMAGE_REF]= ($image=== "")? "Image Invalide": null;
            $this->data_error[TYPE_REF]= ($type=== "")? "Type Invalide": null;
            $this->data_error[NUMBER_REF]= ($number=== "")? "Numéro Invalide": null;
            $this->data_error[DESCRIPTION_REF]= ($description=== "")? "description Invalide": null;
            return false;
        }
    }

    public function noScript($value){
        $value= htmlentities($value);
        return $value;
    }
}