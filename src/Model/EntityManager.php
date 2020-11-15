<?php


namespace Btinet\Ringhorn\Model;

use \ReflectionClass;
use \ReflectionProperty;
use \ReflectionException;
use \Exception;
use Btinet\Ringhorn\Logger;

class EntityManager
{
    protected $db;
    protected $entity;

    function __construct()
    {
       $this->db = new Database();
    }

    public function persist($entity, $id = false){
        self::generateReflectionClass($entity);
        $class_name = strtolower($this->entity->getShortName());
        foreach($this->entity->getProperties() as $property){
            foreach ($property as $key => $value){
                if($key == 'name'){
                    $rp = new ReflectionProperty($entity, $value);
                    if($rp->isInitialized($entity)){
                        $mvalue = ucfirst($value);
                        $method = "get$mvalue";

                        $data[$value] = $entity->$method();
                    }
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

    public function remove($entity, $id)
    {
        self::generateReflectionClass($entity);
        $class_name = strtolower($this->entity->getShortName());
        if ($id) {
            $row = $this->db->select("SELECT * FROM $class_name WHERE id = :id", ['id' => $id]);
            if ($row) {
                return $this->db->delete($class_name, ['id' => $id]);
            }
        } else {
            return false;
        }
    }

    public function truncate($entity)
    {
        self::generateReflectionClass($entity);
        $class_name = strtolower($this->entity->getShortName());
        try {
            return $this->db->truncate($class_name);
        } catch (Exception $e){
            Logger::newMessage($e);
            Logger::customErrorMsg($e);
        }
    }

    protected function generateReflectionClass($entity){
        try {
            return $this->entity = new ReflectionClass($entity);
        } catch (ReflectionException $reflectionException){
            Logger::newMessage($reflectionException);
            Logger::customErrorMsg($reflectionException);
        }
    }

}

