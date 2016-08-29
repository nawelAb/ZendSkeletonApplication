<?php
namespace Forms\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\AbstractTableGateway;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
 
// use Zend\Db\Sql\Adapter\Platefotrm\Mysql;
// use Zend\Db\Adapter\Platform\Mysql;

class Formulaires extends AbstractTableGateway
{
    protected $tableGateway;

    public function __construct($adapter)
    {
        $this->table = 'forms';

        $this->adapter = $adapter;

        $this->initialize();
    }

    public function Leases($formId)
    {
        // $result = $this->select(function (Select $select) use ($poolid) {
        //     $select
        //         ->columns(array(
        //             'ipaddress',
        //             'accountid',
        //             'productid',
        //             'webaccountid'
        //         ))
        //         ->join('account', 'account.accountid = ipaddress_pool.accountid', array(
        //             'firstname',
        //             'lastname'
        //         ))
        //         ->join('product_hosting', 'product_hosting.hostingid = ipaddress_pool.hostingid', array(
        //             'name'
        //         ))
        //         ->join('webaccount', 'webaccount.webaccountid = ipaddress_pool.webaccountid', array(
        //             'domain'
        //         ))->where->equalTo('ipaddress_pool.poolid', $poolid);
        // });

        // return $result->toArray();


        $result = $this->select(function (Select $select) use ($formId) {
            $select
            ->columns('*')  
           
            ->join('forms', 'form_comment.form_id = forms.id', array())
            ->join('comments', 'form_comment.comment_id = comments.id', array())
            ->where('forms.id ='.$formId);
            // ->join('account', 'account.accountid = ipaddress_pool.accountid', array(
            //         'firstname',
            //         'lastname'
            //     ))
            //     ->join('product_hosting', 'product_hosting.hostingid = ipaddress_pool.hostingid', array(
            //         'name'
            //     ))
            //     ->join('webaccount', 'webaccount.webaccountid = ipaddress_pool.webaccountid', array(
            //         'domain'
            //     ))->where->equalTo('ipaddress_pool.poolid', $poolid);
        });

        return $result->toArray();


        // >from('form_comment')->columns(array('comment_id'))
        //     ->join('forms', 'form_comment.form_id = forms.id', array(), Select::JOIN_LEFT)
        //     ->join('comments', 'form_comment.comment_id = comments.id', array(), Select::JOIN_LEFT)
        //     ->where('forms.id ='.$formId);
    }


    // public function __construct(TableGateway $tableGateway)
    // {
    //     $this->tableGateway = $tableGateway;      
    // }

    public function JoinfetchAll($formId)
    {
        
        
        $sql = $this->tableGateway->getSql();  
     
        $sql->select()
            ->from('form_comment')->columns(array('comment_id'))
            ->join('forms', 'form_comment.form_id = forms.id', array(), Select::JOIN_LEFT)
            ->join('comments', 'form_comment.comment_id = comments.id', array(), Select::JOIN_LEFT)
            ->where('forms.id ='.$formId);

        // $select->getSqlString();
        return $this->tableGateway->selectWith($sql);

        // $sqlSelect = $this->tableGateway->getSql()
        //                   ->select()
        //                   ->join('track', 'track.album_id = album.id', array('*'), 'left');
 
        // return $this->tableGateway->selectWith($sqlSelect);
    }        


    public function getFormTag($formId) {
        
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

    public function getFormComment($formId) {
        
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