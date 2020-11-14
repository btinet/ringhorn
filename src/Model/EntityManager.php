<?php


namespace Btinet\Ringhorn\Model;

use \ReflectionClass;

class EntityManager
{
    protected $db;
    protected $entity;

    function __construct()
    {
       $this->db = new Database();
    }

    public function persist($entity, $id = false){
        $this->entity = new ReflectionClass($entity);
        $class_name = strtolower($this->entity->getShortName());
        foreach($this->entity->getProperties() as $property){
            foreach ($property as $key => $value){
                if($key == 'name'){
                    $value = strtoupper($value);
                    $method = "get$value";
                    $data[$value] = $entity->$method();
                }
            }
        };
        if ($id){
            $row = $this->db->select("SELECT * FROM $class_name WHERE id = :id", ['id' => $id]);
            if ($row){
                return $this->db->update($class_name, $data, ['id' => $id]);
            }
        } else {
            return $this->db->insert($class_name, $data);
        }
    }

    public function remove($entity, $id = false){
        $this->entity = new ReflectionClass($entity);
        $class_name = strtolower($this->entity->getShortName());
        if ($id){
            $row = $this->db->select("SELECT * FROM $class_name WHERE id = :id", ['id' => $id]);
            if ($row){
                return $this->db->delete($class_name, ['id' => $id]);
            }
        } else {
            return false;
        }
    }

}

