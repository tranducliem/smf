<?php
/**
 * Description of user_m
 *
 * @author daolm
 */
class User_m extends MY_Model
{
    protected $_table = "default_users";

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_users_list($limit, $offset, $base_where = array()){
        if(!empty($base_where)){
            if($base_where['username'] != ''){
                $this->db->like('username', $base_where['username']);
            }
        }
        if(!empty($limit) && !empty($offset)) {
            parent::limit($limit, $offset);
        }
        return parent::get_all();
    }
}
