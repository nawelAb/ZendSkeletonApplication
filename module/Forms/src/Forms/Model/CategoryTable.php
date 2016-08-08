<?php
namespace Forms\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;


class CategoryTable
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

    public function getCatgegory($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCategory(FormsModel $form)
    {
        // pour Zend\Db\TableGateway\TableGateway les donnes doivent etre dans un tableau non un objet 
        $data = array(           
            'id'   => $category->id,
            'value' => $category->value,                    
        );
        $id = (int)$category->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        }         
    }
    
    public function deleteCategory($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }    
}