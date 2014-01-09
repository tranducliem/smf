<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Definition Class
 * @Description: This is a model class process crud for definition table
 * @Author: tranducliem
 * @Date: 11/20/13
 * @Update: 11/20/2013
 */

class Definition_m extends MY_Model {

    protected $_table = "definition";

    public function __construct(){
        parent::__construct();
    }

    public function get_definition_list($limit, $offset, $base_where = array()){
        if(!empty($base_where)){
            if($base_where['title'] != ''){
                $this->db->like('title', $base_where['title']);
            }
        }
        parent::limit($limit, $offset);
        parent::order_by('id', 'ASC');
        return parent::get_all();
    }
} 