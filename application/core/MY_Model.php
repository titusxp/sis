<?php

class MY_Model extends CI_Model{
    const DB_TABLE = 'abstract';
    const DB_TABLE_PK = 'abstract';
    
    //create record
    protected function insert() {
        $this -> db -> insert($this::DB_TABLE, $this);
        $this -> {$this::DB_TABLE_PK} = $this -> db -> insert_id();
    }

    //update record
    protected function update() {
        $this->db->where($this::DB_TABLE_PK, $this->{$this::DB_TABLE_PK});
        $this->db->update($this::DB_TABLE, $this); //, $this::DB_TABLE_PK);
    }
    
    /*
     * populate from an array or standard class.
     * @param mixed $row
    */
    public function populate($row){
        foreach($row as $key => $value){
            if(property_exists($this, $key))
            {
                $this -> $key = $value;
            }
        }        
    }
    /*
     * Load from database
     * @param int @id
     */
    public function load($id){
        $query = $this-> db -> get_where($this::DB_TABLE, array($this::DB_TABLE_PK => $id,));
        $this ->populate($query -> row());
    }
    
    /*
     * Delete the current record
     */
    public function delete(){
    $this -> db -> delete($this::DB_TABLE, array($this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK},));
    unset($this->{$this::DB_TABLE_PK});
    }
    
    /*
     * Delete the record with the passed ID
     */
    public function delete_by_id($id){
        $this -> db -> delete($this::DB_TABLE, array($this::DB_TABLE_PK => $id,));
    }
    
    /*
     * Save the record
     */
    public function save(){ 
        if(isset($this->{$this::DB_TABLE_PK}) && $this->{$this::DB_TABLE_PK} > 0)
        { 
            $this->update();
        }
        else
        { 
            $this->insert();
        }
    }
    
    /*
     * Get an array of Models with an optional limit, offset
     * 
     * @param int $limit Optional
     * @param int $offset Optional, if set, requries $limit
     * @return array of Models populated by databse, keyed by PK
     */
    public function get($limit = 0, $offset = 0){
        if($limit){
            $query = $this->db->get($this::DB_TABLE, $limit, $offset);
        }
        else{
            $query = $this->db->get($this::DB_TABLE);
        }
        $ret_val = array();
        $class = get_class($this);
        foreach($query->result() as $row){
            $model = new $class;
            $model->populate($row);
            $ret_val[$row->{$this::DB_TABLE_PK}] = $model;
        }
        return $ret_val;
    }
    
    public function get_where($searchArray)
    {
        $query = $this->db->get_where($this::DB_TABLE, $searchArray);

        $ret_val = array();
        $class = get_class($this);
        
        foreach($query->result() as $row){
            $model = new $class;
            $model->populate($row);
            $ret_val[] = $model;
        }
        return $ret_val;
    }
    
}

