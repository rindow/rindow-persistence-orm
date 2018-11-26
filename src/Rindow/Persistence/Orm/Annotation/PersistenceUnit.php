<?php
use Rindow\Persistence\Orm\Annotation;

/**
*  Expresses a dependency on an EntityManagerFactory and its associated persistence unit.
* 
* @Annotation
* @Target({ TYPE,METHOD,FIELD })
*/ 
class PersistenceUnit
{
    /**
    * (Optional) The name by which the entity manager factory is to be accessed in the environment referencing context; not needed when dependency injection is used.
    * @var String
    */
    public $name;

    /**
    * (Optional) The name of the persistence unit as defined in the persistence.xml file.
    * @var String
    */
    public $unitName;
}
