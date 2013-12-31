<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Blog module
 *
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Blog
 */
class Module_News extends Module
{
	public $version = '1.0.0';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'News',
				'ar' => 'المدوّنة',
				'br' => 'News',
				'pt' => 'News',
				'el' => 'Ιστολόγιο',
                'fa' => 'بلاگ',
				'he' => 'בלוג',
				'id' => 'News',
				'lt' => 'Newsas',
				'pl' => 'News',
				'ru' => 'Блог',
				'tw' => '文章',
				'cn' => '文章',
				'hu' => 'News',
				'fi' => 'Newsi',
				'th' => 'บล็อก',
				'se' => 'Newsg',
			),
			'description' => array(
				'en' => 'Post news entries.',
				'ar' => 'أنشر المقالات على مدوّنتك.',
				'br' => 'Escrever publicações de News',
				'pt' => 'Escrever e editar publicações no news',
				'cs' => 'Publikujte nové články a příspěvky na news.', #update translation
				'da' => 'Skriv newsindlæg',
				'de' => 'Veröffentliche neue Artikel und News-Einträge', #update translation
				'sl' => 'Objavite news prispevke',
				'fi' => 'Kirjoita newsi artikkeleita.',
				'el' => 'Δημιουργήστε άρθρα και εγγραφές στο ιστολόγιο σας.',
				'es' => 'Escribe entradas para los artículos y news (web log).', #update translation
                'fa' => 'مقالات منتشر شده در بلاگ',
				'fr' => 'Poster des articles d\'actualités.',
				'he' => 'ניהול בלוג',
				'id' => 'Post entri news',
				'it' => 'Pubblica notizie e post per il news.', #update translation
				'lt' => 'Rašykite naujienas bei news\'o įrašus.',
				'nl' => 'Post nieuwsartikelen en newss op uw site.',
				'pl' => 'Dodawaj nowe wpisy na newsu',
				'ru' => 'Управление записями блога.',
				'tw' => '發表新聞訊息、部落格等文章。',
				'cn' => '发表新闻讯息、部落格等文章。',
				'th' => 'โพสต์รายการบล็อก',
				'hu' => 'News bejegyzések létrehozása.',
				'se' => 'Inlägg i newsgen.',
			),
			'frontend' => true,
			'backend' => true,
			'skip_xss' => true,
			'menu' => 'content',

			'roles' => array(
				'put_live', 'edit_live', 'delete_live'
			),

			'sections' => array(
				'posts' => array(
					'name' => 'news:posts_title',
					'uri' => 'admin/news',
					'shortcuts' => array(
						array(
							'name' => 'news:create_title',
							'uri' => 'admin/news/create',
							'class' => 'add',
						),
					),
				),
				'categories' => array(
					'name' => 'cat:list_title',
					'uri' => 'admin/news/categories',
					'shortcuts' => array(
						array(
							'name' => 'cat:create_title',
							'uri' => 'admin/news/categories/create',
							'class' => 'add',
						),
					),
				),
			),
		);

		if (function_exists('group_has_role'))
		{
			if(group_has_role('news', 'admin_news_fields'))
			{
				$info['sections']['fields'] = array(
							'name' 	=> 'global:custom_fields',
							'uri' 	=> 'admin/news/fields',
								'shortcuts' => array(
									'create' => array(
										'name' 	=> 'streams:add_field',
										'uri' 	=> 'admin/news/fields/create',
										'class' => 'add'
										)
									)
							);
			}
		}

		return $info;
	}

	public function install()
	{
		$this->dbforge->drop_table('news_categories');

		$this->load->driver('Streams');
		$this->streams->utilities->remove_namespace('newss');

		// Just in case.
		$this->dbforge->drop_table('news');

		if ($this->db->table_exists('data_streams'))
		{
			$this->db->where('stream_namespace', 'newss')->delete('data_streams');
		}

		// Create the news categories table.
		$this->install_tables(array(
			'news_categories' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'slug' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true, 'key' => true),
				'title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true),
			),
		));

		$this->streams->streams->add_stream(
			'lang:news:news_title',
			'news',
			'newss',
			null,
			null
		);

		// Add the intro field.
		// This can be later removed by an admin.
		$intro_field = array(
			'name'		=> 'lang:news:intro_label',
			'slug'		=> 'intro',
			'namespace' => 'newss',
			'type'		=> 'wysiwyg',
			'assign'	=> 'news',
			'extra'		=> array('editor_type' => 'simple', 'allow_tags' => 'y'),
			'required'	=> true
		);
		$this->streams->fields->add_field($intro_field);

		// Ad the rest of the news fields the normal way.
		$news_fields = array(
				'title' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => false, 'unique' => true),
				'slug' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => false),
				'thumbnail' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => false),
				'category_id' => array('type' => 'INT', 'constraint' => 11, 'key' => true),
				'body' => array('type' => 'TEXT'),
				'parsed' => array('type' => 'TEXT'),
				'keywords' => array('type' => 'VARCHAR', 'constraint' => 32, 'default' => ''),
				'author_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
				'created_on' => array('type' => 'INT', 'constraint' => 11),
				'updated_on' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
				'comments_enabled' => array('type' => 'ENUM', 'constraint' => array('no','1 day','1 week','2 weeks','1 month', '3 months', 'always'), 'default' => '3 months'),
				'status' => array('type' => 'ENUM', 'constraint' => array('draft', 'live'), 'default' => 'draft'),
				'type' => array('type' => 'SET', 'constraint' => array('html', 'markdown', 'wysiwyg-advanced', 'wysiwyg-simple')),
				'preview_hash' => array('type' => 'CHAR', 'constraint' => 32, 'default' => ''),
		);
		return $this->dbforge->add_column('news', $news_fields);
	}

    public function uninstall()
    {
        if($this->dbforge->drop_table('news'))
        {
            return TRUE;
        }
    }

	public function upgrade($old_version)
	{
		return true;
	}
}
