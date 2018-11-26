<?php
use Rindow\Persistence\Orm\Annotation;

/**
*  Expresses a dependency on a container-managed EntityManager and its associated persistence context.
* 
* @Annotation
* @Target({ TYPE,METHOD,FIELD })
*/ 
class PersistenceContext
{
    /**
    * (Optional) The name by which the entity manager is to be accessed in the environment referencing context; not needed when dependency injection is used.
    * @var String
    */
    public $name;

    /**
    * (Optional) Properties for the container or persistence provider.
    * @var array<PersistenceProperty>
    */
    public $properties;

    /**
    * (Optional) Specifies whether the persistence context is always automatically synchronized with the current transaction or whether the persistence context must be explicitly joined to the current transaction by means of the EntityManager joinTransaction method.
    * @enum("SYNCHRONIZED","UNSYNCHRONIZED")
    * @var SynchronizationType
    */
    public $synchronization;

    /**
    * (Optional) Specifies whether a transaction-scoped persistence context or an extended persistence context is to be used.
    * @enum("EXTENDED","TRANSACTION")
    * @var PersistenceContextType
    */
    public $type;

    /**
    * (Optional) The name of the persistence unit as defined in the persistence.xml file.
    * @var String
    */
    public $unitName;
}
