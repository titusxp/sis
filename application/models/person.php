<?php

class Person extends MY_Model{
    const DB_TABLE = 'persons';
    const DB_TABLE_PK = 'person_id';
    
    public $person_id;
    
    public $person_name;
    
    public $email;
    
    public $phone_number;
    
    public $date_of_birth;
    
    public $gender;
    
    public $nationality;
    
    public $address;
    
    public $picture;
    
    
    

}
