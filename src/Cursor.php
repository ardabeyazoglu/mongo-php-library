<?php

namespace MongoDB;

use MongoDB\Operation\Find;

/**
 * Created for compatibility layer for legacy mongodb driver (only for Find method)
 * @author Arda Beyazoglu
 * @package MongoDB
 */
class Cursor implements \IteratorAggregate{

    private $_realCursor;
    private $_collection;
    private $_filter;
    private $_options;

    public function __construct(Collection &$collection, $filter = [], array $options = [])
    {
        $this->_collection = $collection;
        $this->_filter = $filter;
        $this->_options = $options;
    }

    public function getIterator(){
        $operation = new Find($this->_collection->getDatabaseName(), $this->_collection->getCollectionName(), $this->_filter, $this->_options);
        $server = $this->_collection->manager->selectServer($this->_options['readPreference']);
        $this->_realCursor = $operation->execute($server);

        return $this->_realCursor;
    }

    public function count(){
        return $this->_collection->count($this->_filter, $this->_options);
    }

    public function sort($sort){
        $this->_options["sort"] = $sort;
        return $this;
    }

    public function limit($limit){
        $this->_options["limit"] = $limit;
        return $this;
    }

    public function skip($skip){
        $this->_options["skip"] = $skip;
        return $this;
    }

}

?>