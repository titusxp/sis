<?php

class Student_Guardian extends MY_Model{
    const DB_TABLE = 'student_guardians';
    const DB_TABLE_PK = 'student_guardian_id';
    
    public $student_guardian_id;
    
    public $person_id;
    
    public $student_id;
    
    public $relationship;
    

}
