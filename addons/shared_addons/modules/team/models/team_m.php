<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * The Test Class
 * @Description: This is a model class process crud for test table
 * @Author: tranducliem
 * @Date: 11/20/13
 * @Update: 11/20/2013
 */
class Team_m extends MY_Model {

    protected $_table = "team";

    public function __construct() {
        parent::__construct();
    }

    public function get_team_list($limit, $offset, $base_where = array()) {
        $this->db
            ->select('team.*,company.title as company')
            ->join('company', 'team.company_id = company.id');
        if (!empty($base_where)) {
            if ($base_where['title'] != '') {
                $this->db->like('team.title', $base_where['title']);
            }
        }
        parent::limit($limit, $offset);
        return parent::get_all();
    }

}
