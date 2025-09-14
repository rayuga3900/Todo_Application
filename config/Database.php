<?php
//best practice to give class same name as filename

//this class is holding database information and the connection with database
class Database{
private $db_host='localhost';
private $db_user='root';
private $db_password='';
private $db_name='todo_app_mysqli_oop';
//mysqli is specific to mysql and has more features
//PDO is good when you wish to scale application
//or use different databases

public $conn;
public function connect(){
    $this->conn=null;
    try{
        $this->conn=new mysqli($this->db_host,$this->db_user,$this->db_password,$this->db_name);
        //below code if to check whether connection was succesful
        if($this->conn->connect_error)
        {
            die("Connection failed".$this->conn->connect_error);
        }
        // echo "Yeah it works";
    }
    //we use try-catch for unexpected error related to connection itself
    //but occurs during instantiation of mysqli object
    catch(Exception $error)
    {
        echo "Connection Error".$error->getMessage();
    }
    return $this->conn;
}

}


?>