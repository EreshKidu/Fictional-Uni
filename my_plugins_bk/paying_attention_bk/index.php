<?php 

/*
Plugin Name: Are you paying attention quiz
Description: Multiple choice
Version: 1.0
Author: Eresh

*/

if ( ! defined ('ABSPATH') ) exit; //Exit if accessed directly

class AreYouPayingAttention {
    function __construct (){
        add_action ('init', array ($this, 'adminAssets'));

    }

    function adminAssets(){
        wp_register_style ('quizeditcss', plugin_dir_url(__FILE__) . 'build/index.css');
        wp_register_script ('ournewblocktype', plugin_dir_url(__FILE__) . 'build/index.js', array('wp-blocks', 'wp-element', 'wp-editor'));
        register_block_type ("ourplugin/are-you-paying-attention", array(
            'editor_script' => 'ournewblocktype',
            'editor_style' => "quizeditcss",
            'render_callback' => array($this, 'theHTML')
        ));
    }

    function theHTML($attributes){
        ob_start(); ?>
<p>Today theddddddddd sky is <?php echo $attributes['skyColor']; ?> and the grass is <?php echo $attributes['grassColor']; ?> '123</p>'


<?php return ob_get_clean();
    }

}

$areyoupayingattention = new AreYouPayingAttention ();