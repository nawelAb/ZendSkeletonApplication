<?php
namespace Tags\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

class TagsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
	
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getTag($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveTag(TagsModel $tag)
    {
        
        $data = array(           
            'id'   => $tag->id,
            'value' => $tag->value,                    
        );
    
        $id = (int)$tag->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Tag id does not exist');
            }
        }
    }
    
    public function deleteTag($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }    
}