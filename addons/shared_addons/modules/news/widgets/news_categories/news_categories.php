<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show a list of News categories.
 *
 * @author        Stephen Cozart
 * @author        PyroCMS Dev Team
 * @package       PyroCMS\Core\Modules\News\Widgets
 */
class Widget_News_categories extends Widgets
{
	public $author = 'Stephen Cozart';

	public $website = 'http://github.com/clip/';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'News Categories',
		'br' => 'Categorias do News',
		'pt' => 'Categorias do News',
		'el' => 'Κατηγορίες Ιστολογίου',
		'fr' => 'Catégories du News',
		'ru' => 'Категории Блога',
		'id' => 'Kateori News',
            'fa' => 'مجموعه های بلاگ',
	);

	public $description = array(
		'en' => 'Show a list of News categories',
		'br' => 'Mostra uma lista de navegação com as categorias do News',
		'pt' => 'Mostra uma lista de navegação com as categorias do News',
		'el' => 'Προβάλει την λίστα των κατηγοριών του ιστολογίου σας',
		'fr' => 'Permet d\'afficher la liste de Catégories du News',
		'ru' => 'Выводит список категорий блога',
		'id' => 'Menampilkan daftar kategori tulisan',
            'fa' => 'نمایش لیستی از مجموعه های بلاگ',
	);

	public function run()
	{
		$this->load->model('News/News_categories_m');

		$categories = $this->News_categories_m->order_by('title')->get_all();

		return array('categories' => $categories);
	}

}
