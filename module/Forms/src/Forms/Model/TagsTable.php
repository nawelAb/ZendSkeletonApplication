<?php
namespace Forms\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Zend\Db\Sql\Select;


class TagsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
	
    public function getFormTag($formId) {
        
        $sql = $this->tableGateway->getSql();
    
        $select = new Select();
        $select->from('form_tag')->columns(array('tag_id'))
            ->join('forms', 'form_tag.form_id = forms.id', array(), Select::JOIN_LEFT)
            ->join('comments', 'form_tag.tag_id = comments.id', array(), Select::JOIN_LEFT)
            ->where('forms.id ='.$formId);

            // \Zend\Debug\Debug::dump($select->getSqlString()); die;
        // $select->getSqlString();
        return $this->tableGateway->selectWith($select)->current();
        // return  $sql->getSqlstringForSqlObject($sql);
     //    $resultSet = $this->tableGateway->selectWith($sql);
     //        \Zend\Debug\Debug::dump($sql->getSqlString(new Zend\Db\Adapter\Platform\Mysql())); die;
     //    $oSelect->getSqlString(new Zend\Db\Adapter\Platform\Mysql());
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