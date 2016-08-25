<?php
namespace Forms\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Forms\Model\CommentsModel;

class CommentsTable 
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

    public function getComment($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveComment(CommentsModel $comment)
    {
        // pour Zend\Db\TableGateway\TableGateway les donnes doivent etre dans un tableau non un objet 
        $data = array(           
            'id'   => $comment->id,
            'value' => $comment->value,                    
        );
    
        $id = (int)$comment->id;
        if ($id == 0) {
             $this->tableGateway->insert($data);
             return $id = $this->tableGateway->lastInsertValue;        

        } else {
            
            //ERREUR
            //return $id = $this->tableGateway->lastInsertValue;        
               
        }
    }

    public function getId()
    {
        return $id = $this->tableGateway->lastInsertValue;  
    }
    
    public function deleteComment($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }    
}