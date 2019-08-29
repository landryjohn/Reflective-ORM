<?php

class Post {

    public $id ;

    public $title;

    public $body;

    public function __construct($id , $title , $body ){
        $this->id = $id ;
        $this->title = $title ;
        $this->body = $body ;  
    }

    public function save(){
        $class = new \ReflectionClass($this);
        $tableName = strtolower($class->getShortName());

        $propToImplode = [];

        foreach( $class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property ){
            // consider only public pproperties of the providen
            $propertyName = $property->getName();
            var_dump( $this ); 
            die();
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

    }

}

$post = new Post( 0 , "Nativescript" , "Awesome Framework !");

$post->save();