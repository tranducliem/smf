<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Unity extends Theme {

    public $name = 'Unity';
    public $author = 'Trainning PHP Team';
    public $author_website = 'http://framgia.com/vn/company/vn/index.html';
    public $website = 'http://framgia.com/vn/company/vn/index.html';
    public $description = 'Unity template.';
    public $version = '1.0.0';
    public $options = array(
        'show_breadcrumbs' => 	array(
            'title' 		=> 'Show Breadcrumbs',
            'description'   => 'Would you like to display breadcrumbs?',
            'default'       => 'yes',
            'type'          => 'radio',
            'options'       => 'yes=Yes|no=No',
            'is_required'   => true
        ),
        'slider' => 			array(
            'title'         => 'Show Slider',
            'description'   => 'Would you like to display slider?',
            'default'       => 'yes',
            'type'          => 'radio',
            'options'       => 'yes=Yes|no=No',
            'is_required'   => true
        ),
    );
}

/* End of file theme.php */