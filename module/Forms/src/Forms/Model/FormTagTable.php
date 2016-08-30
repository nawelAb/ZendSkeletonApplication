<?php
namespace Forms\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Zend\Db\Sql\Select;

class FormTagTable
{
    protected $tableGateway;


    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
       
    }

	public function getFormTags($formId) {
        
        // $sql = $this->tableGateway->getSql();
    
        // $select = new Select();
        // $select->from('form_tag')->columns(array('id','tag_id', 'form_id'))
        //     ->join('forms', 'form_tag.form_id = forms.id', array())
        //     ->join('comments', 'form_tag.tag_id = comments.id', array())
        //     ->where('forms.id ='.$formId);


        //     $select = new Select();
        // $select->from('form_tag')->columns(array('id','tag_id', 'form_id'))
        //     ->join('comments', 'form_tag.tag_id = comments.id', array())
        //     ->where('forms.id ='.$formId);




        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('id','tag_id', 'form_id'))
                  ->join('tags', 'tags.id = form_tag.tag_id', array('id','value'), 'left')
                  ->join('forms', 'forms.id = form_tag.form_id', array('id','form_name'), 'left')
                  ->where('forms.id ='.$formId);
        $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
        $resultSet = $statement->execute();
        return $resultSet;
 // "prepareStatementForSqlObject"
 //  [11] => string(24) "getSqlStringForSqlObject"
 //  [12] => string(14) "buildSqlString"

            \Zend\Debug\Debug::dump(get_class_methods($this->tableGateway->getSql()->getSqlStringForSqlObject($sqlSelect))); die;


        // $select->getSqlString();
            // \Zend\Debug\Debug::dump($this->tableGateway->selectWith($select)->next()); die;
        return $this->tableGateway->selectWith($select)->current();
        // return  $sql->getSqlstringForSqlObject($sql);
            \Zend\Debug\Debug::dump($select->getSqlString()); die;
     //    $resultSet = $this->tableGateway->selectWith($sql);
     //    $oSelect->getSqlString(new Zend\Db\Adapter\Platform\Mysql());
    } 


    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getFormTag($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveFormTag(FormTagModel $form)
    {
        // pour Zend\Db\TableGateway\TableGateway les donnes doivent etre dans un tableau non un objet 
        $data = array( 
            'id'      => $form->id,    
            'form_id'   => $form->form_id,
            'tag_id' => $form->tag_id             
        );
       
        $id = (int)$form->id;
        if ($id == 0) {
            $this->tableGateway->insert($data); 
            return $id = $this->tableGateway->lastInsertValue;         
        }
    }
    
    public function deleteFormTag($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }  

   
}