<?php
require_once('Entity.php');

class User extends Entity {

    protected $tableName = 'user';

    public $id ;

    public $username ;

    public $password ;

}



