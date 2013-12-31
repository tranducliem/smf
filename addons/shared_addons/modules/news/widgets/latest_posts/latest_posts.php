<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show Latest news in your site with a widget.
 *
 * Intended for use on cms pages. Usage :
 * on a CMS page add:
 *
 *     {widget_area('name_of_area')}
 *
 * 'name_of_area' is the name of the widget area you created in the  admin
 * control panel
 *
 * @author  Erik Berman
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\news\Widgets
 */
class Widget_Latest_posts extends Widgets
{

	public $author = 'Erik Berman';

	public $website = 'http://www.nukleo.fr';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Latest posts',
		'br' => 'Artigos recentes do news',
            'fa' => 'آخرین ارسال ها',
		'pt' => 'Artigos recentes do news',
		'el' => 'Τελευταίες αναρτήσεις ιστολογίου',
		'fr' => 'Derniers articles',
		'ru' => 'Последние записи',
		'id' => 'Post Terbaru',
	);

	public $description = array(
		'en' => 'Display latest news posts with a widget',
		'br' => 'Mostra uma lista de navegação para abrir os últimos artigos publicados no news',
            'fa' => 'نمایش آخرین پست های وبلاگ در یک ویجت',
		'pt' => 'Mostra uma lista de navegação para abrir os últimos artigos publicados no news',
		'el' => 'Προβάλει τις πιο πρόσφατες αναρτήσεις στο ιστολόγιό σας',
		'fr' => 'Permet d\'afficher la liste des derniers posts du news dans un Widget',
		'ru' => 'Выводит список последних записей блога внутри виджета',
		'id' => 'Menampilkan posting news terbaru menggunakan widget',
	);

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of posts',
		)
	);

	public function form($options)
	{
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
		// load the news module's model
		class_exists('news_m') OR $this->load->model('news/news_m');

		// sets default number of posts to be shown
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		// retrieve the records using the news module's model
		$news_widget = $this->news_m
			->limit($options['limit'])
			->get_many_by(array('status' => 'live'));

		// returns the variables to be used within the widget's view
		return array('news_widget' => $news_widget);
	}

}
