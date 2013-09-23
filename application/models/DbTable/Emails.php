<?php

class Application_Model_DbTable_Emails extends Zend_Db_Table_Abstract
{

    protected $_name = 'emails';
    protected $_primary = 'to';

    public function getEmailsToSend($force = false)
    {
        $select = "`when` < '" . date('Y-m-d H:i:s') . "'";
        if($force)
            $select = null;
        $rows = $this->fetchAll($select);
        if(!$rows)
            return null;
        $this->delete($select);
        return $rows->toArray();
    }
}

