<?php


namespace Btinet\Ringhorn\Model;


use \ReflectionClass;

abstract class EntityRepository
{
    protected Database $db;
    protected $entity;

    function __construct(ReflectionClass $entity)
    {
        $this->db = new Database();
        $this->entity = $entity;
    }

    public function findAll(){
        return $this->db->select('SELECT * FROM '.$this->entity->getShortName().' ');
    }

    public function findBy($citeria = array(), $sort = false){

        if (!empty($citeria)){
            $first = true;
            $criteria_data = null;
            foreach ($citeria as $property => $value){
                $word = ($first) ? 'WHERE' : 'AND';
                $criteria_data .= "$word $property = :$property";
                $first = false;
            }
        }

        return $this->db->select('SELECT * FROM '.$this->entity->getShortName().' '.$criteria_data, $citeria);

    }

}
