<?php
//shortcodes as widgets on text widget
add_filter('widget_text', 'do_shortcode');

/*          SHORTCODE
*** to show portfolio or authors of courses ***
*/
add_shortcode( 'xtraslms_portfolio', 'xtras_learndash_portfolio' );
function xtras_learndash_portfolio( $atts ){
    global $post;
    //default args
    $args = shortcode_atts( array(
        'tax' => 'cat', //'tag' or 'cat'
        'user_id' => '',
        // f (filter), p (portf), a (author),
        // al (list of courses per author)
        'show' => 'f,p',
        'cols' => 2,
        'progress' => 1,
        'my' => 1,
        'q' => -1, //quantity
        'orderby' => 'modified', //rand
        'iclass' => 'thumbnail'
    ), $atts );
    //columns
    $cols = $args['cols']*1;
    if (!in_array($cols, array(1,2,3,4,5)))
        $cols = 2;
    //other args
    $tax = $args['tax'];
    if ($tax=='tag') $tax='post_tag';
    if ($tax=='cat') $tax='category';
    $q = $args['q'];
    $orderby = $args['orderby'];
    $iclass = $args['iclass'];
    if (!$args['user_id'])
        $user_id = get_current_user_id();
    $show = $args['show'];
    $show_f = strpos(' '.$show, 'f');
    $show_p = strpos(' '.$show, 'p');
    $show_a = strpos(' '.$show, 'a');
    $show_l = strpos(' '.$show, 'l');
    if (!$show_f && !$show_p && !$show_a)
        $show_p = 1;
    $only_author = 0;
    if (!$show_f && !$show_p && $show_a)
        $only_author = 1;
    $show_progress = $args['progress'];
    $show_mycourses = $args['my'] && !$only_author;
    //my courses
    $my_courses = array();
    if ($user_id && $show_mycourses)
        $my_courses = ld_get_mycourses($user_id);

    //get courses
    $list = xtrasfwd_getcourses($tax, $q, $orderby, $only_author);
    $the_query = $list[0];
    $allterms = $list[1];
    $quantity = $list[2];
    $allauthors = $list[3];

    //if have courses or authors
    $html ='';
    if ( $the_query->have_posts() || !empty($allauthors)){

        /* FILTER */
        $show_my_f = false;
        if ($user_id && $show_mycourses)
            $show_my_f = true;
        if ($show_f)
        $html .= build_filter_isotope_btns(
            'sfwd-courses',
            'category',
            array(
                'f_id'        => 'filters',
                'show_my_f'   => $show_my_f,
                'my_class'    => 'mycourses',
                'my_label'    => __('My courses')
            )
        );

        /* PORTFOLIO */
        if ($show_p || $show_a):

        //html to return
        $html .= '<div id="portfolio" class="lms_isotope';
        if ($only_author) $html.=' professors';
        $html.='">';

        /** COURSES **/
        if(!$only_author)
        while ( $the_query->have_posts() ) :
            $the_query->the_post();
            $course_id = get_the_ID();
            //get progress of each course
            $percentage = 0;
            if ($user_id && $show_progress) {
                $progress =learndash_course_progress(
                    array(
                    'user_id'   => $user_id,
                    'course_id' => $course_id,
                    'array'     => true
                    )
                );
                $percentage = $progress['percentage'];
                $status = ( $percentage == 100 ) ? esc_html__('Completed', 'xtras-learndash') : esc_html__('Progress','xtras-learndash').'<span>'. $percentage. '%</span>';
            }
            //get terms for each post
            $pterm = '';
            $terms = get_the_terms($course_id, $tax);
            if ($terms && !is_wp_error($terms)):
                $pterms = array();
                foreach ( $terms as $term )
                    $pterms[] =sanitize_title($term->name);
                $pterm = implode(" ", $pterms);
                //my courses
                if (in_array($course_id, $my_courses) || $percentage)
                    $pterm .= " mycourse";
            endif;
            //tax name into element-item class
            $html .= '<div class="element-item '. $pterm. '"><a href="'.get_permalink().'"  rel="tooltip" title="'.get_the_excerpt().'"><div class="thumbnail">';
            if ($percentage > 0)
                $html .= '<span class="perc">'.$status.'</span>';
            $html .= get_the_post_thumbnail().'</div><h2>'.get_the_title().'</h2></a>';
            if ($show_a)
                $html .= '<h3>'.get_the_author().'</h3>';
            $html .= '</div>';
        endwhile;

        /** AUTHORS **/
        if ($only_author)
        foreach($allauthors as $auth_id=>$courses_ids){
            $html .= '<div class="element-item">';
            //author
            $name =get_the_author_meta('display_name',$auth_id);
            $descr = get_the_author_meta('description',$auth_id);
            $url=get_author_posts_url($auth_id);
            $html .= '<a href="'.$url.'" rel="tooltip" title="'.$descr.'">';
            //img
            $imgsrc = '';
            if (frontend_is_plugin_active('profile-xtra/profilextra.php'))
                $imgsrc = get_user_meta($auth_id, 'profilextra_imgsrc',true);
            if ($imgsrc)
                $avatar = '<img src="'. $imgsrc .'" class="'. $iclass .'"/>';
            else
                $avatar = get_avatar( $auth_id, '120', '', '', array('class'=>$iclass) );
            if ($avatar)
                $html .= $avatar;
            //name
            $html.= '<h2>'.$name."</h2>";
            $html .= '</a>';
            //list of courses
            if ($show_l):
            $html.='<div class="proflist">';
            $html .= '<h3>'.esc_html__('COURSES of this Professor','xtras-learndash').'</h3>';
            foreach ($courses_ids as $c_id)
                $html.='<a href="'.get_permalink($c_id).'" title="'.esc_html__('Go to course','xtras-learndash').'">'.get_the_title($c_id).'</a><br />';
            $html .= '</div>';
             endif;

            $html .= '</div>';
        }
        $html.='</div>';
        endif; //end PORTFOLIO

        $html .='<div class="clear"></div>';

    } else {
        // no courses found
    }
    //restore original post data
    wp_reset_postdata();

    ?>
    <style>
        .element-item {width:<?php echo round(100/$cols, 2).'%!important';?>}
    </style>
    <?php

    return $html;
}


/*** FUNCTIONS ***/
function xtrasfwd_getcourses($this_taxonomy, $q=-1, $orderby='modified', $only_author=0){

    //THE QUERY
    $args = array(
        'post_type' => 'sfwd-courses', 'post_status' => 'published', 'posts_per_page' => $q,
        'orderby'=> $orderby);
    $the_query = new WP_Query( $args );
    //total of courses
    $quantity = $the_query->found_posts;
    //THE LOOP
    $allterms = array(); $terms = '';
    $allauthors = array();
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $ID = get_the_ID();
            if (!$only_author):
                $terms = get_the_terms($ID,
                $this_taxonomy);
            else:
                $author_id = get_the_author_meta('ID');
                $allauthors[$author_id][]=$ID;
            endif;
            if (!empty($terms))
                foreach ($terms as $term)
                    $allterms[] = $term->name;
        }
    }
    return array(
        $the_query,
        $allterms,
        $quantity,
        $allauthors
    );
}

/*** common functions ***/
if (!function_exists( 'frontend_is_plugin_active' )){
    function frontend_is_plugin_active($plg){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        // check for plugin using plugin name
        //'plugin-directory/plugin-file.php'
        if ( is_plugin_active($plg  ) )
            return true;
        return false;
    }
}

/*
**  This function returns the html of buttons (with classes
    of terms for a given taxonomy), ready for isotope filtering
*/
if (!function_exists('build_filter_isotope_btns')) {
    function build_filter_isotope_btns( $post_type, $taxonomy, $args){
        $parse = wp_parse_args($args, array(
            'f_id'          => 'filter_btns',
            'f_class'       => 'button-group',
            'f_title'       => __('Filter'),
            'all_class'     => 'button',
            'all_label'     => __('All'),
            'q'             => true,
            'show_my_f'     => 0,
            'my_class'      => 'my',
            'my_label'      => __('My'),
        ));
        $f='';
        //user
        //if (is_user_logged_in())
        $show_my_f = $parse['show_my_f'];
        //query
        $qargs = array( 'post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => -1 );
        $the_query = new WP_Query( $qargs );
        //total
        $q=$parse['q'];
        if ($q) $q = $the_query->found_posts;
        //THE LOOP
        $allterms = array();
        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                //terms for post_tag or category or other taxonomy
                $terms = get_the_terms(get_the_ID(),$taxonomy);
                if (!empty($terms))
                    foreach ($terms as $term)
                        $allterms[] = $term->name;
            }
        }
        if (empty($allterms)) return $f;
        $terms = array_unique($allterms);
        if (count($terms)==1) return $f;
        $f = "<div id='". $parse['f_id'] ."' class='". $parse['f_class'] ."'>";
        $f .= "<span>".$parse['f_title']."</span>";
        $all = $parse['all_class'];
        if ($show_my_f)
            $f .= "<button class='". $all ." ". $parse['my_class'] ."' data-filter='.". $parse['my_class'] ."'>". $parse['my_label'] ."</button>";
        $f .= "<button class='". $all ." is-checked' data-filter='*'>". $parse['all_label'];
        if ($q) $f .= " (".$q.")";
        $f .= "</button>";
        if ( count($terms) > 0 )
            foreach ( $terms as $term )
                $f .= "<button class='". $all ."' data-filter='.".sanitize_title($term)."'>". $term."</button>";
        $f .= "</div>";
        //restore original post data
        wp_reset_postdata();
        //returns buttons for isotope
/*
         if (is_user_logged_in()) $f.= "is ".is_user_logged_in()."<---<br />";
    else
        $f.= "is not ".is_user_logged_in()."<---<br />";

*/
        return $f;
    }
}
