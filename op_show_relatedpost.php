<?php
/**
 * Plugin Name: Opacity-Showing Related Posts 
 * Description: After active this pluginyou will able to show your related posts in your post contetnt automatically.[Note: it will show posts per page 5. If you want you can modify it from the function]
 * Version: 1.0
 * Plugin URI: 
 * Author: Sagar Dash
 * Author URI: opacity.blog.com
 * License: GPL v2 or later
 *

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
//avoid direct calls to this file
if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

//functions start from here
add_filter('the_content', function($content) {
  $id = get_the_id();
  if (!is_singular('post')) {
    return $content;
  }
  $terms = get_the_terms($id, 'category');
  $cats = array();
  foreach ($terms as $term) {
    $cats[] = $term->cat_ID;
  }
  $loop = new WP_Query(
          array(
            'posts_per_page' => 5,
            'category__in' => $cats,
            'orderby' => 'rand',
            'post__not_in' => array($id)
          )
        );
      if ($loop->have_posts() )  {
          $content .='
            <h2>You Also Might Like</h2>
            <ul class="related-category-posts">';
          while ($loop->have_posts()) {
            $loop->the_post();
            $content .='
            <li>
              <a href="'.get_permalink().'">'.get_the_title().'</a>
            </li>';
          }
          $content .= '</ul>';
          wp_reset_query();
      }
      return $content;
});
























?>
