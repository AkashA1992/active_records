<?php  
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class accounts extends collection {
    protected static $modelName = 'account';
}

class todos extends collection {
    protected static $modelName = 'todo';
}

class collection {
    static public function create() {
        $model = new static::$modelName;
        return $model;
    }
    
    static public function find($id) {
    
        $columnName="*";
        $condition=" id=$id";
        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql=buildSqlQuery::selectQuery($columnName,$tableName,$condition);        
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);        
        $recordsSet =  $statement->fetchAll(PDO::FETCH_ASSOC);        
        return $recordsSet;
    }
    
    static public function findAll() {
                
        $columnName="*";
        $condition=" 1=1 ";
        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql=buildSqlQuery::selectQuery($columnName,$tableName,$condition);        
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);        
        $recordsSet =  $statement->fetchAll(PDO::FETCH_ASSOC);        
        return $recordsSet;
    }    
}

class account extends model {
    public function __construct()
    {
        $this->tableName = 'accounts';	
    }
}

class todo extends model {
    public $id;
    public $owneremail;
    public $ownerid;
    public $createddate;
    public $duedate;
    public $message;
    public $isdone;

    public function __construct()
    {
        $this->tableName = 'todos';	
    }
}

class model {
    protected $tableName;
    public function save()
    {
        $tableName=$this->tableName;
        $array = get_object_vars($this);        
        
        if ($this->id == '') {
            $columnString = implode(',', array_slice(array_keys($array),0,count($array)-1));
            $valueString = 15 . implode(',', array_slice($array,0,count($array)-1));
        
            $sql = $this->insert($tableName,$columnString,$valueString);
            $this->executeQuery($sql);
        } 
        else {      
                   
            $valueString='';                       
            
            foreach($array as $key=>$values){
                if($values!= null && $values!='' && $values!=$this->id && $values!=$this->tableName){                    
                    $valueString.=$key."=".$values.",";                
                }
            }
            $valueString=substr($valueString,0,strlen($valueString)-1);        
            $sql = $this->update($tableName,$valueString,$this->id);
            $this->executeQuery($sql);
        }                
    }
    
    public function remove(){
        $tableName=$this->tableName;
        $sql = $this->delete($tableName,$this->id);
        $this->executeQuery($sql);
    }
    private function insert($tableName,$columnString,$valueString) {
        $sql = buildSqlQuery::insertQuery($tableName,$columnString,$valueString);
        return $sql;
    }
    private function update($tableName,$columnString,$valueString) {
        $sql = buildSqlQuery::updateQuery($tableName,$columnString,$valueString);
        return $sql;        
    }
    public function delete($tableName,$id) {
        $sql = buildSqlQuery::deleteQuery($tableName,$id);
        return $sql;        
    }
    
    public function executeQuery($sql){
        $db = dbConn::getConnection();
        $statement = $db->prepare($sql);
        $statement->execute();
    }
    
}

class buildSqlQuery{
    static public function selectQuery($columnName,$tableName,$condition){
        return "SELECT ".$columnName." FROM ". $tableName ." where ".$condition;
    }    
   
    static public function insertQuery($tableName,$columnString,$valueString){
        return "INSERT INTO $tableName ($columnString) VALUES($valueString)";                
    }
    
    static public function updateQuery($tableName,$columnString,$id){
        return "UPDATE $tableName SET $columnString WHERE id=$id";                        
    }    
    
    static public function deleteQuery($tableName,$id){
        return "DELETE FROM $tableName WHERE id=$id";                        
    }    

}


class dbConn{    
    protected static $db;        
        
    private function __construct() {
        try {
        
        $serverName = "sql1.njit.edu";
        $userName = "ara59";
        $password = "CkyYZ4sSq";
        $tableName= "accounts";
        $condition="id<6";
        $columnName="*";
                    
            self::$db = new PDO( 'mysql:host=' . $serverName .';dbname=' . $userName, $userName, $password );
            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch (PDOException $e) {            
            echo "Connection Error: " . $e->getMessage();
        }
    }
        
    public static function getConnection() {    
        if (!self::$db) {            
            new dbConn();
        }        
        return self::$db;
    }
}

class table extends createBody{
           
    static public function createTable($htmlEntity){
        
        $html='';           
        $html.='<table border="1">';
        
        foreach($htmlEntity as $output){
          $html.='<tr>';
          foreach($output as $data){            
              $html.='<td>'.$data.'</td>';                      
          }          
          $html.='</tr>';
        }
        $html.='</table>';        
        return $html;        
    }
}

class createBody {
    protected $html;  
    
    public function __construct(){        
        $this->html .= '<html>';        
        $this->html .= '<body>';        
    }        
        
    public function __destruct(){
        $this->html .= '</body></html>';        
    }  
}

class stringFunctions{
    
    public static function printOutput($message){
        print_r($message);
    }
}       
        $result='';  
        $result.='Select record from Accounts:';
        $records = accounts::find(1);        
        $result.=table::createTable($records).'<hr>';
        
        $result.='Select all records from Accounts:';
        $records = accounts::findAll();        
        $result.=table::createTable($records).'<hr>';
        
        $result.='Select record from Todos:';
        $records = todos::find(1);        
        $result.=table::createTable($records).'<hr>';
        
        $result.='Select all record from Todos:';
        $records = todos::findAll();        
        $result.=table::createTable($records).'<hr>';
        
        
        
        
        $recordDelete = new todo();
        $recordDelete->id =15;
        $recordDelete->remove();
        
        $recordInsert = new todo();
        $recordInsert->id = '';
        $recordInsert->owneremail = '"xyz@njit.edu"';
        $recordInsert->ownerid=8;
        $recordInsert->createddate='"2017-12-19 00:00:00"';
        $recordInsert->duedate='"2017-12-20 00:00:00"';
        $recordInsert->message='"Test Insert"';
        $recordInsert->isdone=0;
        $recordInsert->save();
        $result.='Record Inserted Successfully!!<br>';
        
        $recordUpdate = new todo();
        $recordUpdate->id =12;
        $recordUpdate->ownerid=1001;
        $recordUpdate->createddate='"2017-12-19 00:00:00"';        
        $recordUpdate->save();
        $result.='Record Updated Successfully!!';        
        stringFunctions::printOutput($result);

?>