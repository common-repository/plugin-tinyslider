<?php
/*
Plugin Name: PluginTinySlider
Plugin URI: http://www.dimgoto.com/open-source/wordpress/plugins/plugin-tinyslider
Description: Simple Images Slider, <a href="http://www.leigeber.com/" target="_blank">TinySlider (Michael Leigeber, Web Designer)</a> implementation.
Version: 1.1.0
Author: Dimitri GOY
Author URI: http://www.dimgoto.com
*/

/*  Copyright 2009  DimGoTo  (email : wordpress@dimgoto.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Classe PluginTinySlider.
 *
 * This Plugin is based on TinySlider powered by Michael Leigeber, implementation to WordPress.
 *
 * @package Plugins
 * @subpackage TinySlider
 * @version 1.1.0
 * @author Dimitri GOY
 * @copyright 2009 - DimGoTo
 * @link http://www.dimgoto.com/
 * @link http://www.leigeber.com/
 */
class PluginTinySlider {

	private $_baseurl;
	private $_basedir;

	function __construct() {
		$this->_baseurl = WP_PLUGIN_URL . '/' . str_replace('\\', '/', dirname(plugin_basename(__FILE__)));
		$this->_basedir = '/' . PLUGINDIR . '/' . str_replace('\\', '/', dirname(plugin_basename(__FILE__)));

		load_plugin_textdomain(get_class($this), $this->_basedir);
		
		wp_enqueue_script('jquery');
		
		if (is_admin()) {

			register_activation_hook(__FILE__, array($this, 'activate'));
			register_deactivation_hook(__FILE__, array($this, 'deactivate'));

			if (function_exists('register_uninstall_hook')) {
	    		register_uninstall_hook(__FILE__, array($this, 'uninstall'));
			}
			
			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('admin_head', array($this, 'admin_head'));
		} else {
			/* CSS File? */
			if (!file_exists(get_bloginfo('template_directory') . '/tinyslider.css')) {
				wp_enqueue_style('tinyslider-style', $this->_baseurl . '/tinyslider.css', array(), false, 'screen');
			} else {
				wp_enqueue_style('tinyslider-style', get_bloginfo('template_url') . '/tinyslider.css', array(), false, 'screen');
			}
			wp_enqueue_script('tinyslider-js', $this->_baseurl . '/tinyslider.js', array(), false, false);
			add_action('wp_head', array($this, 'wp_head'));
			add_shortcode(strtolower(get_class($this)), array($this, 'tinyslider_shortcode'));
		}
	}
	
	public function activate() {
	}

	public function deactivate() {
	}
		
	public function uninstall() {	
	}
	
	public function admin_menu() {

		add_submenu_page('plugins.php',
			__('TinySlider', get_class($this)),
			__('TinySlider', get_class($this)),
			'activate_plugins',
			get_class($this),
			array($this, 'admin_control')
		);
	}

	public function admin_head() {
	}

	public function wp_head() {
	}
	
	public function admin_control() {
		$html = '';
		$html .= '<div class="wrap" id="' . strtolower(get_class($this)) . '">';
		$html .= '<div id="icon-options-general" class="icon32"><br /></div>';
		$html .= '<h2>' . sprintf(__('Utilisation de %s', get_class($this)), get_class($this)) . '</h2>';
		$html .= '<br/>';
		$html .= '<div>';
		$html .= '<h3>' . __('Attributs du shortcode', get_class($this)) . '</h3>';
		$html .= '<p><strong>id:</strong> ' . __('Id parent du diaporama principal', get_class($this)) . '</p>';
		$html .= '<p><strong>auto:</strong> ' . __('Seconds animation automatique', get_class($this)) . '</p>';
		$html .= '<p><strong>resume:</strong> ' . __('Résume auto après interruption, défaut: true', get_class($this)) . '</p>';
		$html .= '<p><strong>vertical:</strong> ' . __('Direction, défaut: false', get_class($this)) . '</p>';
		$html .= '<p><strong>navid:</strong> ' . __('Id du UL de navigation, optionel', get_class($this)) . '</p>';
		$html .= '<p><strong>activeclass:</strong> ' . __('Class css du LI courant', get_class($this)) . '</p>';
		$html .= '<p><strong>position:</strong> ' . __('Index initial du slide, défault: 0', get_class($this)) . '</p>';
		$html .= '</div>';
		$html .= '<div>';
		$html .= '<h3>' . __('Exemple', get_class($this)) . '</h3>';
		$html .= '<p class="description">' . __('Copiez le code suivant, collez dans une page ou post, remplacez les chemin d\'image.', get_class($this)) . '</p>';
		$html .= '<code>';
		$html .= '[plugintinyslider id="slider" navid="pagination" auto="3" vertical="false"]';
		$html .= htmlentities('<div>
<div class="sliderbutton"><img src="http://localhost/wordpress/wp-content/uploads/belle-ile-en-mer/images/left.gif" width="32" height="38" alt="Previous" onclick="slideshow.move(-1)" /></div>
<div class="slider" id="slider">
<ul>
<li>
<h1>TinySlider - Simple JavaScript Slideshow</h1>
<p>This super lightweight (1.5 KB) sliding JavaScript slideshow script can easily be customized to integrate with any website through CSS. You can add any content to it, not just 
images, and it gracefully degrades without JavaScript support. The script supports automatic rotation with the option to auto-resume, an active class on a navigation list if 
applicable, and a direction toggle (vertical or horizontal).<br/>
<em>For complete details visit <a href="http://www.leigeber.com/" class="external">leigeber.com</a> and WP implementation <a href="http://www.dimgoto.com/" 						class="external">dimgoto.com</a>.</em></p>
</li>
<li><img src="http://localhost/wordpress/wp-content/uploads/belle-ile-en-mer/photos/sea-turtle.jpg" width="400" height="250" alt="Sea turtle" /></li>
<li><img src="http://localhost/wordpress/wp-content/uploads/belle-ile-en-mer/photos/coral-reef.jpg" width="400" height="250" alt="Coral Reef" /></li>
<li><img src="http://localhost/wordpress/wp-content/uploads/belle-ile-en-mer/photos/blue-fish.jpg" width="400" height="250" alt="Blue Fish" /></li>
</ul>
</div>
<div class="sliderbutton"><img src="http://localhost/wordpress/wp-content/uploads/belle-ile-en-mer/images/right.gif" width="32" height="38" alt="Next" onclick="slideshow.move(1)" />
</div>
</div>
<ul id="pagination" class="pagination">
<li onclick="slideshow.pos(0)">1</li>
<li onclick="slideshow.pos(1)">2</li>
<li onclick="slideshow.pos(2)">3</li>
<li onclick="slideshow.pos(3)">4</li>
</ul>
</div>');
		$html .= '[/plugintinyslider]';
		$html .= '</clode>';
		$html .= '</div>';
		$html .= '</div>';
		echo $html;
	}
	
	public function tinyslider_shortcode($attrs, $content = null, $code = '') {
		extract(shortcode_atts(
			array(
				'id'		=> '',
				'auto'		=> 3,
				'resume'	=> 'true',
				'vertical'	=> 'false',
				'navid'		=> '',
				'position'	=> 0
			),
			$attrs)
		);
		$html = '';
		if (!empty($content) 
		&& !is_null($content)) {
			$html .= '<div class="sliderwrap">';
			$html .= $content;
			$html .= '</div>';
			$html .= '<script type="text/javascript">';
			$html .= 'var the' . $id . '=new TINY.slider.slide(\'the'. $id . '\',{';
			$html .= '	id:\'' . $id . '\',';
			$html .= '	auto:' . $auto . ',';
			$html .= '	resume:' . $resume . ',';
			$html .= '	vertical:' . $vertical . ',';
			$html .= '	navid:\'' . $navid . '\',';
			$html .= '	activeclass:\'' . $activeclass . '\',';
			$html .= '	position:' . $position . '';
			$html .= '	});';
			$html .= '</script>';
		}
		return $html;
	}
}
new PluginTinySlider();
?>
