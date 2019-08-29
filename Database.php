<?php

namespace AppBlog ;

use \PDO ;

/**
 * Class Database
 * Use to interact with the database 
 * SGBD support : MySQL
 */
class Database{

    /**
     * @var string
     */
    protected $SGBDName;

    /**
     * 
     * @var String
     */
    protected $dsn ;
    
    /**
     *
     * @var PDO object 
     */
    protected $pdo ;
    
    /**
     *
     * @var String
     */
    protected $dbName ; 
    
    /**
     *
     * @var String
     */
    protected $host ;
    
    /**
     *
     * @var String 
     */
    protected $username ;
    
    /**
     *
     * @var String
     */
    protected $password ;
    
    /**
     *
     * @var String 
     */
    protected $errorConnection; 
    
    /**
     *
     * @var boolean
     */
    protected $connectionStatus = false ;
    
    /**
     *
     * @var String
     */
    protected $errorInfo ;

    /**
     * update de value of DSN
     */
    protected function updateDSN(){
        $this->dsn = $this->SGBDName . ':host=' . $this->host . ';dbname=' . $this->dbName ;
    }
    
    /**
     * Construct the Database object
     * @param string $SGBDName
     * @param string $host
     * @param string $dbName
     * @param string $username
     * @param string $password
     */
    public function __construct( string $SGBDName , string $host = '127.0.0.1', 
                                 string $dbName, string $username = 'root' , string $password) {
        
        $this->SGBDName = $SGBDName ;
        $this->host = $host ;
        $this->dbName = $dbName ;
        $this->username = $username ;
        $this->password = $password ;
         $this->dsn = $SGBDName . ':host=' . $host . ';dbname=' . $dbName ;
        //$this->updateDSN() ;
    }
    
    
    /**
     * establish connection with the database
     */
    public function databaseConnection(){

        try{
            
            if( $this->pdo === null ){
                
                $this->pdo = new PDO( $this->dsn , $this->username , $this->password ) ;
                $this->pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connectionStatus = true ;
                
            }
        
        }catch( \Exception $e){

            $this->errorConnection = $e->getMessage() ; 
    
        }

    }
    
    /**
     * Check if the connection is establish
     * @return bolean
     */
    public function isConnect(){
       
        return $this->connectionStatus ;
    
    }
    
    /**
     * return the a string that check the error connection
     * @return String
     */
    public function getErrorStatement(){
        return $this->errorInfo ;
    }

    /**
     * Allow you to execute SQL query
     * @param $statement
     * @param $className
     * @return array
     */
    public function query( $statement, $className , $arrayResultForm = true ){
    
        $this->databaseConnection() ;
        
        if( $this->isConnect() ) {
        
            $statementResult = $this->pdo->query( $statement ) ;
            
            $this->errorInfo = $this->pdo->errorInfo() ;
            
            if( $arrayResultForm == true )
                return $statementResult->fetchAll( PDO::FETCH_CLASS , $className  ) ;
            else
                return $statementResult->fetch( PDO::FETCH_CLASS , $className  ) ;
                
        
        }else {
            
            $this->errorInfo = "Not connect to the database" ;
        }
        
    }
    
    /**
     * 
     * @param string $statement
     * @param array $args
     * @param string $className
     * @param boolean $arrayResultForm
     * @return \PDO::FETCH_CLASS
     */
    public function prepare( $statement , $args = [] , $className , $arrayResultForm = true ){
        
        $this->databaseConnection() ;
        
        if( $this->isConnect() ){
            
            $request = $this->pdo->prepare( $statement ) ;
            
            $request->execute( $args ) ;
            
            $request->setFetchMode( PDO::FETCH_CLASS , $className ) ;
            
            if( $arrayResultForm ){

                return $request->fetchAll() ;
                
            }else{
            
                return $request->fetch() ;
            
            }
            
        
        }else {
            
            $this->errorInfo = "Not connect to the database" ;
            
        }
        
    }
    
}

