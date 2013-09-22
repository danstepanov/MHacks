<?php

class Application_Model_DbTable_SearchMap extends Zend_Db_Table_Abstract
{

    protected $_name = 'search_map';
    
    public function getModelFromMake($make)
    {
        $select = $this->select()
            ->from($this, array('model'))
            ->where('make = ?' , $make)
            ->group(array('model'))
            ->order('model ASC');
        $rows = $this->fetchAll($select);
        if(!$rows)
        	return null;
        return $rows->toArray();
    }
    
    public function getYearFromMakeModel($make, $model)
    {
        $select = $this->select()
            ->from($this, array('year'))
            ->where('make = ?' , $make)
            ->where('model = ?' , $model)
            ->group(array('year'))
            ->order('year ASC');
        $rows = $this->fetchAll($select);
        if(!$rows)
        	return null;
        return $rows->toArray();
    }
    
    public function getStyles($make, $model, $year)
    {
    	$select = $this->select()
    	->from($this, array('style'))
    	->where('make = ?' , $make)
    	->where('model = ?' , $model)
    	->where('year = ?' , $year)
    	->group(array('style'))
    	->order('style ASC');
    	$rows = $this->fetchAll($select);
    	if(!$rows)
    		return null;
    	return $rows->toArray();
    }
    
    public function getUVC($make, $model, $year, $style)
    {
        $select = $this->select()
            ->from($this, array('uvc'))
            ->where('make = ?' , $make)
            ->where('model = ?' , $model)
            ->where('year = ?' , $year)
            ->where('style = ?' , $style)
            ->group(array('uvc'))
            ->order('uvc ASC');
        $rows = $this->fetchAll($select);
        if(!$rows)
        	return null;
        return $rows->toArray();
    }
    
    public function getMakes()
    {
        $select = $this->select()
                    ->from($this, array('make'))
                    ->group(array('make'))
                    ->order('make ASC');
        $rows = $this->fetchAll($select);
        if(!$rows)
            return null;
        return $rows->toArray();
    }
}

