<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Test Class
 * @Description: This is a model class process crud for feedback type manager
 * @Author: laiminhdao
 * @Date: 12/31/2013
 * @Update: 12/31/2013
 */

class Company_m extends MY_Model {

    protected $_table = "company";

    public function __construct(){
        parent::__construct();
    }

    public function get_companies_list($limit, $offset, $base_where = array()){
        if(!empty($base_where)){
            if($base_where['title'] != ''){
                $this->db->like('title', $base_where['title']);
            }
        }
        parent::limit($limit, $offset);
        return parent::get_all();
    }
} 