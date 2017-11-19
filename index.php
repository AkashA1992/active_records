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
    public function __construct()
    {
        $this->tableName = 'todos';	
    }
}

class model {
    protected $tableName;
    public function save()
    {
        if ($this->id = '') {
            $sql = $this->insert();
        } else {
            $sql = $this->update();
        }
        $db = dbConn::getConnection();
        $statement = $db->prepare($sql);
        $statement->execute();        
        $tableName = get_called_class();
        $array = get_object_vars($this);
        $columnString = implode(',', $array);
        $valueString = ":".implode(',:', $array);       
        echo 'I just saved record: ' . $this->id;
    }
    private function insert() {
        $sql = 'sometthing';
        return $sql;
    }
    private function update() {
        $sql = 'sometthing';
        return $sql;
        echo 'I just updated record' . $this->id;
    }
    public function delete() {
        echo 'I just deleted record' . $this->id;
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
        print_r($html);        
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

class buildSqlQuery{
    static public function selectQuery($columnName,$tableName,$condition){
        return "SELECT ".$columnName." FROM ". $tableName ." where ".$condition;
    }
    
    static public function selectCount($columnName,$tableName,$condition){
        return "SELECT count(".$columnName.") FROM ". $tableName ." where ".$condition;
    }
}
        
        $records = accounts::find(1);        
        table::createTable($records);
        
        $records = accounts::findAll();        
        table::createTable($records);
        
        $records = todos::find(1);        
        table::createTable($records);
        
        $records = todos::findAll();        
        table::createTable($records);
        
?>