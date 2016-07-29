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

    public function getTag($tag_id)
    {
        $tag_id  = (int) $tag_id;
        $rowset = $this->tableGateway->select(array('tag_id' => $tag_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveTag(TagModel $tag)
    {
        
        $data = array(           
            'tag_id'   => $tag->tag_id,
            'tag_name' => $tag->tag_name,                    
        );
    

        $tag_id = (int)$tag->tag_id;
        if ($tag_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($tag_id)) {
                $this->tableGateway->update($data, array('tag_id' => $tag_id));
            } else {
                throw new \Exception('Tag id does not exist');
            }
        }
    }
    
    public function deleteTag($id)
    {
        $this->tableGateway->delete(array('tag_id' => $tag_id));
    }    
}