<?php

abstract class Entity {

    protected $tableName ; 

    /**
     * @var PDO
     */
    protected $db;

    public function __construct(){
        try{
            $this->db = new \PDO('mysql:host=localhost;dbname=userdb' , 'root' , '');
        }catch(\Exception $e){
            throw new \Exception('Error creating database connection');
        }
    }

    public function save(){
        // this method construct SQL query using Reflection
        $class = new \ReflectionClass($this);
        $tableName = '' ;

        if( $this->tableName == '' ){
            $tableName = strtolower($class->getShortName());
        }else{
            $tableName = $this->tableName ; 
        }

        $propToImplode = [];

        foreach( $class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property ){
            // consider only public pproperties of the providen
            $propertyName = $property->getName();
            $propToImplode[] = ' '.$propertyName.' = "'.$this->{$propertyName}.'"' ;
        }

        $setClause = implode( ',' , $propToImplode );
        $sqlQuery = '' ;
        if( $this->id > 0 ){
            $sqlQuery = 'UPDATE ' . $tableName . ' SET '.$setClause.' WHERE id = '. $this->id ;
        }else{
            $sqlQuery = 'INSERT INTO '. $tableName . ' SET ' . $setClause ;
        }
        
        echo $sqlQuery ; 
        $database = new \PDO('mysql:host=localhost;dbname=userdb' , 'root' , '');
        $database -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $result = $database->query($sqlQuery) or die( print_r( $database->errorInfo() )) ;

        echo $result ;
    }

    /**
     * @return Entity
     */
    public static function morph( array $object ){
        
        $class = new \ReflectionClass( get_called_class() );

        $entity = $class->newInstance();

        foreach( $class->getProperties( \ReflectionProperty::IS_PUBLIC) as $property ){
            if( isset( $object[$property->getName()] ) ){
                $property->setValue( $entity , $object[$property->getName()]);
            }
        }

        return $entity; 
    }

    /**
     * @return Entity
     */
    public function find( $options = [] ){

        $result = [] ;
        $query = 'SELECT * FROM ' . $this->tableName ; 

        $whereClause = '' ; 
        $whereConditions = [] ; 

        if( !empty($options)){
            if( is_array( $options )){
                foreach( $options as $key => $value ){
                    $whereConditions[] = $key . '="' . $value . '" '; 
                }
                $whereClause = ' WHERE ' . implode(' AND ' , $whereConditions);
            }elseif(is_string($options)){
                $whereClause = ' WHERE ' . $options ; 
            }
        }
        $database = new \PDO('mysql:host=localhost;dbname=userdb' , 'root' , '');
        $database -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query .= $whereClause ; 
        echo $query ; 
        $r = $database->query($query) or die( print_r($database->errorInfo()));

        foreach( $r as $row ){
            $result[] = self::morph( $row );
        }
        var_dump( $result );
        return $result;
    }


}