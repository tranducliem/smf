<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * definition Plugin
 *
 * Create lists of posts
 * 
 * @author   PyroCMS Dev Team
 * @package  PyroCMS\Core\Modules\definition\Plugins
 */
class Plugin_Definition extends Plugin
{

	public $version = '1.0.0';
	public $name = array(
		'en' => 'Definition'
	);
	public $description = array(
		'en' => 'A plugin to display information such as definition for keyword.'
	);

	/**
	 * Returns a PluginDoc array
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'view' => array(
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'Display definition posts optionally filtering them by category.'
				),
				'single' => false,// single tag or double tag (tag pair)
				'double' => true,
				'variables' => 'slug',// the variables available inside the double tags
				'attributes' => array(// an array of all attributes
					'slug' => array(// the attribute name. If the attribute name is used give most common values as separate attributes
						'type' => 'slug',// Can be: slug, number, flag, text, any. A flag is a predefined value.
						'flags' => '',// valid flag values that the plugin will recognize. IE: asc|desc|random
						'default' => '',// the value that it defaults to
						'required' => true,// is this attribute required?
						),
					),
				),
			);
		return $info;
	}

	/**
	 * Definition List
	 *
	 * Creates a list of Definition posts. Takes all of the parameters
	 * available to streams, sans stream, where, and namespace.
	 *
	 * Usage:
	 * {{ definition:view slug="5" }}
	 *
	 * @param	array
	 * @return	array
	 */
	public function view(){
        $slug = $this->attribute('slug');
        $query = $this->db
            ->select('description')
            ->where('slug', $slug)
            ->get('definition')
            ->row();
        if($query != null){
            return $query->description;
        }else{
            return "";
        }
	}
}