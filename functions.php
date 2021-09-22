<?php 

//Add search-route as separate file
require get_theme_file_path( '/inc/search-route.php' );
require get_theme_file_path ('/inc/like-route.php' );

//For the post type resurn author as authorName
function uni_custom_rest (){
    register_rest_field('post', 'authorName', array(
        'get_callback' => function () {return get_the_author();}
    )  );

    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function () {return count_user_posts(get_current_user_id(), 'note');}
    )  );


}

//When start rest API. MAny examples in search-route.php
add_action( 'rest_api_init', 'uni_custom_rest' );


//Define banners for all pages. If there is no title/image etc as input then find it. This function is called on all pages.
function pageBanner ($args = NULL){

if (!$args['title']){
    $args['title'] = get_the_title();
}

if (!$args['subtitle']){
    $args['subtitle'] = get_field('page_banner_subtitle');
}

if(!$args['photo']){
    if (get_field('page_banner_background_image') AND !is_archive() AND !is_home()){
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
        $args['photo'] = get_theme_file_uri( '/images/ocean.jpg' );
    }
    

}


?>

<div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url( <?php echo $args['photo']; ?>)"></div>
      <div class="page-banner__content container container--narrow">
          <!-- <?php  print_r($pageBannerImage); ?> -->
        <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle']   ?></p>
        </div>
      </div>
    </div>



<?php }

//Run JS, google APIs, fonts, 'root_url' var
function uni_files(){
    wp_enqueue_script ('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyAq0k8tWilJ5psjCKGQwk-COQGBTjFXTmA', NULL, '1.0', true);
    wp_enqueue_script ('main_uni_js', get_theme_file_uri( '/build/index.js' ), array('jquery'), '1.0', true);
    wp_enqueue_style ('uni_main_styles', get_theme_file_uri( '/build/style-index.css' ));
    wp_enqueue_style ('uni_extra_styles', get_theme_file_uri( '/build/index.css' ));
    wp_enqueue_style ('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style ('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

    wp_localize_script('main_uni_js', 'uniData', array (
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce( 'wp_rest' )
    ));

    
}
add_action('wp_enqueue_scripts', 'uni_files');

//Enagle features for custom theme
function uni_features (){
    // register_nav_menu( 'headerMenuLocation', 'Header Menu Location' );
    // register_nav_menu( 'footerMenuLocationOne', 'Footer Menu Location One' );
    // register_nav_menu( 'footerMenuLocationTwo', 'Footer Menu Location Two' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);


}

add_action( 'after_setup_theme', 'uni_features' );



function uni_adjust_queries ($query){
    //Change query for events, except for admin page 
    if (!is_admin() AND is_post_type_archive('event') and $query->is_main_query()){
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array (
            array (
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
              ) ));
        
    }
    //Change query for programs.
        if (!is_admin() AND is_post_type_archive('program') and $query->is_main_query()){
       //Show all programs always
            $query->set('posts_per_page', -1);
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');

    }

    if (!is_admin() AND is_post_type_archive('campus') and $query->is_main_query()){
       //Show all campuses always
        $query->set('posts_per_page', -1);


}


}

add_action ('pre_get_posts', 'uni_adjust_queries');

//API key for google maps
function uniMapKey ($api){
    $api['key'] = 'AIzaSyAq0k8tWilJ5psjCKGQwk-COQGBTjFXTmA';
    return $api;
}

add_filter ('acf/fields/google_map/api', 'uniMapKey');

//redirect subscribers accounts out of admin and onto home page
add_action( 'admin_init', 'redirectSubsToFrontend' );
function redirectSubsToFrontend () {
    $currentUser = wp_get_current_user(  );
    if (count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber'){
        wp_redirect(site_url('/'));
        exit;

    }
}

add_action( 'wp_loaded', 'noSubsAdminBar' );
function noSubsAdminBar () {
    $currentUser = wp_get_current_user(  );
    if (count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber'){
        show_admin_bar( false );
        

    }
}

//Customize login page
add_filter ('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl (){
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');
function ourLoginCSS (){

    wp_enqueue_style ('uni_main_styles', get_theme_file_uri( '/build/style-index.css' ));
    wp_enqueue_style ('uni_extra_styles', get_theme_file_uri( '/build/index.css' ));
    wp_enqueue_style ('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style ('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

}

add_filter ('login_headertitle', 'ourloginTitle');

function ourloginTitle () {
    return get_bloginfo ('name');
}


//Forece notes to private
add_filter("wp_insert_post_data", 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr){
    if ($data['post_type'] == "note"){
        if (count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']){
            die("You have reached your note limit.");
        }
        $data['post_content'] = sanitize_textarea_field( $data['post_content'] );
        $data['post_title'] = sanitize_textarea_field( $data['post_title'] );


    }


    if ($data['post_type'] == "note" AND $data['post_status'] != 'trash'){

        $data['post_status'] = "private";
    }
    return $data;
}

?>