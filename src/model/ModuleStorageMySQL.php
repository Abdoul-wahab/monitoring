<?php


class ModuleStorageMySQL implements ModuleStorage
{

    private $db;

    /**
     * ModuleStorageMySQL constructor.
     */
    public function __construct($db)
    {
        $this->db = $db;
    }


    public function read($id)
    {
        $stmt= $this->db->prepare('SELECT * FROM modules WHERE id = :id');
        $stmt->execute(array('id'=> $id));
        $result = $stmt->fetch();
        $module = new Module($result['name'],$result['number'], $result['description'], $result['type'], $result['image'], $result['temperature'], $result['time'], $result['nombre'], $result['state']);
        return $module;
    }

    //Lire Tout les modules
    public function readAll()
    {
        $stmt= $this->db->prepare('SELECT * FROM modules');
        $stmt->execute();
        $result = $stmt->fetchAll();
        //var_dump($result);
        $data = array();
        foreach ($result as $key => $module){
            $data[$module['id']]= new Module($module['name'],$module['number'], $module['description'], $module['type'], $module['image'], $module['temperature'], $module['time'], $module['nombre'], $module['state']);
        }
        return $data;
    }

    //Enregistrement en bd du module passé en paramètre
    public function create(Module $m)
    {
        // TODO: Implement create() method.
        $stmt= $this->db->prepare('INSERT INTO modules (name, number, description, type, image, temperature, time, nombre, state) VALUES (:name, :number, :description, :type, :image, :temperature, :time, :nombre, :state)');
        $stmt->execute(array('name'=> $this->noScript($m->getName()), 'number'=> $this->noScript($m->getNumber()), 'description'=> $this->noScript($m->getDescription()), 'type'=> $this->noScript($m->getType()), 'image'=> $this->noScript($m->getImage()), 'temperature'=> $this->noScript($m->getTemperature()),'time'=> $this->noScript($m->getTime()),'nombre'=> $this->noScript($m->getNombre()),'state'=> $this->noScript($m->getState()),));
    }

    // modification des infos du module passé en paramètre avec son id
    public function update($id, Module $obj)
    {
        // TODO: Implement update() method.
        $stmt= $this->db->prepare('UPDATE modules SET name= :name, number= :number, description= :description, type = :type, image= :image, temperature = :temperature, time= :time, nombre= :nombre, state= :state WHERE id= :id');
        $stmt->execute(array('name'=> $this->noScript($obj->getName()), 'number'=> $this->noScript($obj->getNumber()), 'description'=> $this->noScript($obj->getDescription()), 'type'=> $this->noScript($obj->getType()), 'image'=> $this->noScript($obj->getImage()), 'temperature'=> $this->noScript($obj->getTemperature()), 'time'=> $this->noScript($obj->getTime()), 'nombre'=> $this->noScript($obj->getNombre()), 'state'=> $this->noScript($obj->getState()), 'id'=> $id));
    }

    //suppression de la bd du module dont le id es passé en paramètre
    public function delete($id)
    {
        // TODO: Implement delete() method.
        $stmt= $this->db->prepare('DELETE FROM modules WHERE id= :id');
        $stmt->execute(array('id'=> $id));
    }

    //Generation de 100 modules au avec du contenu
    public function generate(){
        $faker = Faker\Factory::create();
        for($i=0; $i<99; $i++) {
            $stmt = $this->db->prepare('INSERT INTO modules(name, number, description, type, image, temperature, time, nombre, state) VALUES (:name, :number, :description, :type, :image, :temperature, :time, :nombre, :state)');
            $stmt->execute(array('name'=> $this->noScript($faker->sentence(3, true)), 'number'=> $this->noScript($faker->numberBetween(1000,9000)), 'description'=> $this->noScript($faker->paragraph(3,true)), 'type'=> $this->noScript($faker->word), 'image'=> $this->noScript($faker->imageUrl(640,480, 'cats')), 'temperature'=> $this->noScript($faker->sentence(3, true)), 'time'=> $this->noScript($faker->sentence(3, true)), 'nombre'=> $this->noScript($faker->sentence(3, true)), 'state' => $this->noScript($faker->sentence(3, true)) ));
        }
    }

    //fonction de Vérification des informations(pour éviter les failles xss)
    public function noScript($value){
        $value= htmlentities($value);
        return $value;
    }
}