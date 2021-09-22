<?php
get_header( ); 
pageBanner(array(
  'title' => 'Past events',
  'subtitle' => get_the_archive_description(),

))

?>


    <div class="container container--narrow page-section">
        <?php 
          $today = date('Ymd');
          $pastEvents = new WP_Query (array(
            //pArameter to make pagination work, get the number from url after paged. 1 is default
            'paged' => get_query_var('paged', 1),

            'post_type' => 'event',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array (
              array (
                'key' => 'event_date',
                'compare' => '<=',
                'value' => $today,
                'type' => 'numeric'
                ) 
            )

          ));

            while ($pastEvents->have_posts()){
                $pastEvents->the_post();
                get_template_part ('/template-parts/content', 'event'); 
                

             }
            //WP pagination does not work with custom query, so we need to modify function call
            echo paginate_links(array(
                'total' => $pastEvents->max_num_pages
            ) );
        ?>
    </div>

<?php get_footer();

?>