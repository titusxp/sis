<?php

class collection_type_cost extends MY_Model{
    const DB_TABLE = 'collection_type_costs';
    const DB_TABLE_PK = 'type_cost_id';
    
    public $type_cost_id;
    
    public $class_id;
    
    public $cost;
    
    public $type_id;
}
