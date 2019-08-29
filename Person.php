<?php 


class Person {

    const NAME_LENGTH_MAX = 50 ;

    protected $name ; 

    public function __construct( $name ){
        $this->name = $name ; 
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name ; 
    }

    public static function generateName(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen( $characters );
        $name = '';
        for( $i = 0 ; $i < self::NAME_LENGTH_MAX ; $i++){
            $name .= $characters[rand(0, $charactersLength -1 )];
        }
        return $name ; 
    }

    public static function echoInfoClass( $object ){

        $reflectionClass = new \ReflectionClass( $object );

        // echo class name (with namespace if any)
        echo "Name: " . $reflectionClass->getName() . '<br>';

        // echo class name without namespace
        echo "Short name: " . $reflectionClass->getShortName() . '<br>' . '<br>' ; 
    
        echo "Constants: " . '<br>' ; 
        var_dump( $reflectionClass->getConstants() );
        foreach( $reflectionClass->getConstants() as $constantName => $constant  ){
            echo $constantName . ' = ' . $constant . '<br>' ; 
        }

        echo "Properties: " ; 
        foreach( $reflectionClass->getProperties() as $property ){
            // list of method modifiers (public, static, ...)
            $propModifiers = \Reflection::getModifierNames($property->getModifiers());
            
            foreach( $propModifiers as $modifier ){
                // echo all modifiers properly to the current property
                echo $modifier . " " ; 
            } 
            // this method of reflectionProperty class allow us to access private or protected data in class 
            $property->setAccessible( true );

            echo $property->getName() . ' = ' . json_encode( $property->getValue($object)) . '<br>';
        }
        echo '<br>';
    
        echo 'Methods:' . '<br>' . '<br>' ;
        foreach( $reflectionClass->getMethods() as $method){
            // list method modifiers (public, static , ...)
            $methodModifiers = \Reflection::getModifierNames( $method->getModifiers());
            foreach( $methodModifiers as $modifier ){
                echo $modifier . ' ' ; 
            }

            echo $method->getName();

            $params = [] ;
            foreach( $method->getParameters() as $methodParam ){
                $currentParam = $methodParam->getName();
                if( $methodParam->isDefaultValueAvailable() ){
                    $currentParam .= '=' . $methodParam->getDefaultValue();
                    if( $methodParam->isDefaultValueConstant()){
                        $currentParam .= '(' . $methodParam->getDefaultValueConstantName() . ')' ; 
                    }
                }
                $params[] = $currentParam ; 
            }
            // glue together pairs paramname - defaultvalue if any
            echo '(' . implode(',' , $params) . ')' . '<br>' ; 
        }
    }

    public function sayHello( $arg1 , $arg2 ){
        echo 'arg1 = ' . $arg1 . ' --- arg2 = ' . $arg2  ; 
    }

    private function privateMethod(){
        echo 'i am a private function';
    }


}

$person = new person( 'Landry John'); 
// Person::echoInfoClass( $person ) ;
// echo $person->{$s}() ; 
$method = 'privateMethod' ; 
$params = ['un', 'deux'] ;

$reflectionMethod = new \ReflectionMethod( $person , $method  );
$reflectionMethod->setAccessible( true );
$reflectionMethod->invoke($person); 
/*try{
    // Ceci va lever un exception dans la mesure ou la methode n'existe pas
    $reflectionMethod = new ReflectionMethod( $person , $method ); 
    // leve une exception si les paramètres sont incorrect
    $reflectionMethod->invokeArgs( $person , $params );

}catch ( \ArgumentCountError $e ){
    echo 'Error calling method ' . $method . ' on Person' ;
}catch ( \ReflectionException $e ){
    echo 'Error calling method ' . $method . ' on Person' ;
}catch( \Exception $e ){
    echo 'Exception error';
}*/




