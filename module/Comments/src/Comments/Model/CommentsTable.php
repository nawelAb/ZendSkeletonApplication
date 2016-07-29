<?php
namespace Comments\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;


class Comments\Table
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

    public function getComment($comment_id)
    {
        $comment_id  = (int) $comment_id;
        $rowset = $this->tableGateway->select(array('comment_id' => $comment_id));
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
            'comment_id'   => $comment->comment_id,
            'comment_name' => $comment->comment_name,                    
        );
        // a remplacer par  getArrayCopy() defini dans Auth
        // $data = $form->getArrayCopy();

        $comment_id = (int)$comment->comment_id;
        if ($comment_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($comment_id)) {
                $this->tableGateway->update($data, array('comment_id' => $comment_id));
            } else {
                throw new \Exception('Comment id does not exist');
            }
        }
    }
    
    public function deleteComment($id)
    {
        $this->tableGateway->delete(array('comment_id' => $comment_id));
    }    
}