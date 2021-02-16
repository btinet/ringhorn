<?php


namespace Btinet\Ringhorn\Model;


use \ReflectionClass;

abstract class EntityRepository
{
    protected Database $db;
    protected $entity;
    protected $table;

    function __construct(ReflectionClass $entity)
    {
        $this->db = new Database();
        $this->entity = $entity;
        $this->table = strtolower($entity->getShortName());
    }

    protected function createSqlConditions(array $criteria, array $falseCriteria = array())
    {
        if (!empty($criteria)){
            $first = true;
            $criteria_data = null;
            foreach ($criteria as $property => $value){
                $word = ($first) ? ' WHERE' : ' AND';
                $criteria_data .= "$word $property = :$property";
                $first = false;
            }
        }

        if (!empty($criteria) && !empty($falseCriteria)){
            foreach ($falseCriteria as $property => $value){
                $criteria_data .= " AND $property != :$property";
            }
        }

        return $criteria_data;
    }

    public function findAll(){
        return $this->db->select('SELECT * FROM '.$this->table.' ');
    }

    public function findBy($criteria = array(), array $sort = array()){

        $criteria_data = $this->createSqlConditions($criteria);
        return $this->db->select('SELECT * FROM '.$this->table.' '.$criteria_data, $criteria);

    }

}
