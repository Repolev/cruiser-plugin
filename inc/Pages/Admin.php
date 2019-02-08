<?php 
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Api\SettingsApi;

/**
* 
*/
class Admin extends BaseController
{
	public $settings;

	public $pages = [];
	public $subpages = [];

	public function __construct()
	{
		$this->settings = new SettingsApi();

		$this->pages = [
			[
				'page_title' => 'Cruiser Plugin', 
				'menu_title' => 'Home', 
				'capability' => 'manage_options', 
				'menu_slug' => 'cruiser_plugin', 
				'callback' => function() { echo '<h1>Cruiser Plugin</h1>'; }, 
				'icon_url' => 'dashicons-shield', 
				'position' => 10
			]
		];

		$this->subpages = [
			[
				'parent_slug'	=>	'cruiser_plugin',
				'page_title'	=>	'Messages', 
				'menu_title'	=>	'Messages', 
				'capability'	=>	'manage_options', 
				'menu_slug'		=>	'cruiser_message', 
				'callback' 		=>	function (){ echo '<h1>Messages</h1>'; },
			],
		];

	}

	public function register() 
	{
		$this->settings->addPages( $this->pages )->withSubPage('Dashboard')->addSubPages( $this->subpages )->register();
	}
}
