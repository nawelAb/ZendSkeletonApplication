<?php
namespace Forms\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;


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

    public function getTagByValue($value)
    {
        $value  = (string) $value;
        $rowset = $this->tableGateway->select(array('value' => $value));
        $row = $rowset->current();
        if (!$row) {
            $rep = 0;
            // echo "pas de formulaire correspondant à ce tag";
            // throw new \Exception("Could not find row $value");
        }
        return $row;
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
            
            return $id = $this->tableGateway->lastInsertValue;   
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Tag id does not exist');
            }
        }
    }
    
    public function delete($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }    
}