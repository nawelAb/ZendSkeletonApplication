<?php
namespace Forms\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
 
// use Zend\Db\Sql\Adapter\Platefotrm\Mysql;
// use Zend\Db\Adapter\Platform\Mysql;

class FormsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;      
    }


    public function formTagGet($formId) {
        
        $sql = $this->tableGateway->getSql();
    
        $select = new Select();
        $select->from('form_tag')->columns(array('tag_id'))
            ->join('forms', 'form_tag.form_id = forms.id', array(), Select::JOIN_LEFT)
            ->join('comments', 'form_tag.tag_id = comments.id', array(), Select::JOIN_LEFT)
            ->where('forms.id ='.$formId);

            \Zend\Debug\Debug::dump($select->getSqlString()); die;
        $select->getSqlString();
        return $this->tableGateway->selectWith($select);
        // return  $sql->getSqlstringForSqlObject($sql);
     //    $resultSet = $this->tableGateway->selectWith($sql);
     //        \Zend\Debug\Debug::dump($sql->getSqlString(new Zend\Db\Adapter\Platform\Mysql())); die;
     //    $oSelect->getSqlString(new Zend\Db\Adapter\Platform\Mysql());
    } 

    public function formCommentget($formId) {
        
        $sql = $this->tableGateway->getSql();
    
        $select = new Select();
        $select->from('form_comment')->columns(array('comment_id'))
            ->join('forms', 'form_comment.form_id = forms.id', array(), Select::JOIN_LEFT)
            ->join('comments', 'form_comment.comment_id = comments.id', array(), Select::JOIN_LEFT)
            ->where('forms.id ='.$formId);

        $select->getSqlString();
        return $this->tableGateway->selectWith($select);
            // \Zend\Debug\Debug::dump(  $select->getSqlString()); die;
        // return  $sql->getSqlstringForSqlObject($sql);
     //    $resultSet = $this->tableGateway->selectWith($sql);


     //    $oSelect->getSqlString(new Zend\Db\Adapter\Platform\Mysql());
    }   


    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getForm($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
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
            'id'   => $form->id,
            'form_name' => $form->form_name,
            'state' =>$form->state                              
        );
      
      var_dump("var");
      die;
        $id = (int)$form->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);          
        } else {                
            if ($this->getForm($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }  
        }
    }
    

    public function deleteForm($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }    
}