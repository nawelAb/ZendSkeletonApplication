<?php
namespace Forms\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;


class FormsTable
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

    public function getForm($form_id)
    {
        $form_id  = (int) $form_id;
        $rowset = $this->tableGateway->select(array('form_id' => $form_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveForm(FormsModel $form)
    {
        // pour Zend\Db\TableGateway\TableGateway les donnes doivent etre dans un tableau non un objet 
        $data = array(           
            'form_id'   => $form->form_id,
            'form_name' => $form->form_name,                    
        );
        // a remplacer par  getArrayCopy() defini dans Auth
        // $data = $form->getArrayCopy();

        $form_id = (int)$form->form_id;
        if ($form_id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($form_id)) {
                $this->tableGateway->update($data, array('form_id' => $form_id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    public function deleteForm($id)
    {
        $this->tableGateway->delete(array('form_id' => $form_id));
    }    
}