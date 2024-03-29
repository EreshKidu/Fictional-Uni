<?php 

/*
Plugin Name: Our Test Plugin
Description: Amazing
Version: 1.0
Author: Eresh

*/

class WordCountAndTimePlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array ($this, 'settings'));
    }

    function settings (){
        add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

        add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting ('wordcountplugin', 'wcp_location', array ('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));

        add_settings_field('wcp_headline', 'Headline text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting ('wordcountplugin', 'wcp_headline', array ('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post statistics'));

        add_settings_field('wcp_wordcount', 'Word count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
        register_setting ('wordcountplugin', 'wcp_wordcount', array ('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        add_settings_field('wcp_charactercount', 'Character count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
        register_setting ('wordcountplugin', 'wcp_charactercount', array ('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        add_settings_field('wcp_readtime', 'Read time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
        register_setting ('wordcountplugin', 'wcp_readtime', array ('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    function sanitizeLocation($input){
        if ($input != '0' AND $input != '1'){
            add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be begin or end');
            return get_option ('wcp_location');

        }

        return $input;

    }
/*
    function charactercountHTML(){ ?>
        <input type="checkbox" name="wcp_charactercount" value='1' <?php  checked(get_option('wcp_charactercount'), '1') ?>>

    <?php }

function readtimeHTML(){ ?>
    <input type="checkbox" name="wcp_readtime" value='1' <?php  checked(get_option('wcp_readtime'), '1') ?>>

<?php }



    function wordcountHTML(){ ?>
        <input type="checkbox" name="wcp_wordcount" value='1' <?php  checked(get_option('wcp_wordcount'), '1') ?>>

    <?php }
    
*/
    function checkboxHTML($args){ ?>
         <input type="checkbox" name="<?php echo $args['theName'] ?>" value='1' <?php  checked(get_option($args['theName']), '1') ?>>
    <?php }

    function headlineHTML(){ ?>
    <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline'));  ?>">

    <?php }

    function locationHTML(){ ?>
        <select name="wcp_location">
            <option value="0" <?php  selected(get_option('wcp_location'), '0'); ?>>Beginning of post</option>
            <option value="1" <?php  selected(get_option('wcp_location'), '1'); ?>>End of post</option>

        </select>
    <?php }


    function adminPage (){
        add_options_page('Word Count Settings', 'Word Count', 'manage_options', 'word-count-settings-page',  array($this, 'ourHtml'));
    }

    function ourHtml (){ ?>
        <div class="wrap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="POST">
                <?php
                    settings_fields('wordcountplugin');
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        </div>


<?php }

    


}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();

