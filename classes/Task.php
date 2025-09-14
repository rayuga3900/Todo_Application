<?php

//this class holds information about tasks
//It also handles respective operations
//like adding a task,deleting a task,...,etc
//by using the database connection(requirement)
class Task{
    private $conn;
    private $table='tasks';
    public $id;
    public $task;
    public $is_completd;

    public function __construct($db){
        $this->conn=$db;
    }
    public function create() {

        $query = 'INSERT INTO ' . $this->table . ' (task) VALUES (?)';
        $stmt = $this->conn->prepare($query);
    //array used for returning values to maintain consistency
    //rather than sending bool or error use array and then send
        if (!$stmt) {
            return [
                'success' => false,
                //when query preparation fails we get error message in
                //$this->conn->errorInfo()[2]
                'error' => $this->conn->errorInfo()[2] // Get the error message
            ];
        }
    
        $stmt->bind_param("s", $this->task);
        
        if (!$stmt->execute()) {
            return [
                'success' => false,

                //when the query execution fails 
                //we get the error  in $stmt->error
                'error' => $stmt->error // Get the execution error
            ];
        }
    
        return [
            'success' => true,
            'message' => 'Task added successfully.'
        ];
    }
    public function read(){
        $query='select * from '.$this->table.' order by created_at desc';
        $result=$this->conn->query($query);
        return $result;
    }
    public function complete($id){
        $query='update '.$this->table.' set is_completed=1 where id=?';
        $stmt=$this->conn->prepare($query);
        $stmt->bind_param("i",$id);
        return $stmt->execute();
         
    }
      public function undoComplete($id){
        $query='update '.$this->table.' set is_completed=0 where id=?';
        $stmt=$this->conn->prepare($query);
        $stmt->bind_param("i",$id);
        return $stmt->execute();
        
    }
    public function delete($id){
        $query='delete from '.$this->table.'  where id=?';
        $stmt=$this->conn->prepare($query);
        $stmt->bind_param("i",$id);
        return $stmt->execute();
        
    }
}
?>