<?php  
ini_set('display_errors', 'On');
error_reporting(E_ALL);

class accounts extends collection {
    protected static $modelName = 'account';
}

class collection {
    static public function create() {
        $model = new static::$modelName;
        return $model;
    }
    
    static public function findAll() {
        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        
        $recordsSet =  $statement->fetchAll(PDO::FETCH_ASSOC);
        //print_r($recordsSet);
        return $recordsSet;
    }
    
}

class account extends model {

    public function __construct()
    {
        $this->tableName = 'accounts';
	
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
        //$statement->fetchAll(PDO::FETCH_ASSOC)
        $tableName = get_called_class();
        $array = get_object_vars($this);
        $columnString = implode(',', $array);
        $valueString = ":".implode(',:', $array);
       // echo "INSERT INTO $tableName (" . $columnString . ") VALUES (" . $valueString . ")</br>";
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
    //variable to hold connection object.
    protected static $db;        
    
    //private construct - class cannot be instantiated externally.
    private function __construct() {
        try {
        
        $serverName = "sql1.njit.edu";
    $userName = "ara59";
    $password = "CkyYZ4sSq";
    $tableName= "accounts";
    $condition="id<6";
    $columnName="*";
            // assign PDO object to db variable
            self::$db = new PDO( 'mysql:host=' . $serverName .';dbname=' . $userName, $userName, $password );
            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch (PDOException $e) {
            //Output error - would normally log this to error file rather than output to user.
            echo "Connection Error: " . $e->getMessage();
        }
    }
    // get connection function. Static method - accessible without instantiation
    public static function getConnection() {
        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$db) {
            //new connection object.
            new dbConn();
        }
        //return connection.
        return self::$db;
    }
}

class createHtml extends pdoConnection{
    
    //public function __construct($hmtlObj){
        
      //  $this->html.=createHtml::generateHtml($hmtlObj);        
    //}
    
    static public function generateHtml($htmlEntity){
        $counter=0;
        $html='';     
        //$html.="Number of records: ".$counter;   
        $html.='<table border="1">';
        
        foreach($htmlEntity as $output){
          $html.='<tr>';
          foreach($output as $data){            
              $html.='<td>'.$data.'</td>';                      
          }
          $counter++;
          $html.='</tr>';
        }
        $html.='</table>';        
        //stringFunctions::printOutput("Number of records: ".$counter);
        print_r($html);        
    }
}

//Used to Open and close PDO connection and fetch data from database
class pdoConnection {
    protected $html;  
    
    public function __construct(){        
        $this->html .= '<html>';        
        $this->html .= '<body>';        
    }        
    
    public static function fetchData($connectionString,$query){
        $stmt = $connectionString->prepare($query); 
        $stmt->execute();
        return $stmt->fetchAll();
    }    
    
    public function __destruct(){
        $this->html .= '</body></html>';        
        //stringFunctions::printOutput($this->html);
    }  
}


        $records = accounts::findAll();
        //print_r($records);
        createHtml::generateHtml($records);
        //$result=new createHtml($records);
        //print_r($result);


?>