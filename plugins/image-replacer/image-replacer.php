<?php
/*
Plugin Name: Image replacer
Plugin URI: https://github.com/brdslls/wordpress-pattern-previewer
Description: Replace images by keywords
Author: brdslls
Version: 0.0.1
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

// Plugin Activation
register_activation_hook(__FILE__, 'ir_activation_function');
function ir_activation_function() {
    // Create tables using the WordPress Database API
    global $wpdb;
    $table_name_keyword = $wpdb->prefix . 'images_by_keyword';
    $table_name_kit = $wpdb->prefix . 'images_by_kit';

    // SQL queries to create the tables
    $sql_keyword = "CREATE TABLE IF NOT EXISTS $table_name_keyword (
        id INT(11) NOT NULL AUTO_INCREMENT,
        images TEXT,
        keyword VARCHAR(255),
        PRIMARY KEY (id)
    )";

    $sql_kit = "CREATE TABLE IF NOT EXISTS $table_name_kit (
        id INT(11) NOT NULL AUTO_INCREMENT,
        images TEXT,
        keyword VARCHAR(255),
        kit VARCHAR(255),
        expiration DATE,
        PRIMARY KEY (id)
    )";

    // Execute the SQL queries
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_keyword);
    dbDelta($sql_kit);
}

// Plugin Deactivation/Removal
register_uninstall_hook(__FILE__, 'ir_deactivation_function');
function ir_deactivation_function() {
    // Delete tables using the WordPress Database API
    global $wpdb;
    $table_name_keyword = $wpdb->prefix . 'images_by_keyword';
    $table_name_kit = $wpdb->prefix . 'images_by_kit';

    $wpdb->query("DROP TABLE IF EXISTS $table_name_keyword");
    $wpdb->query("DROP TABLE IF EXISTS $table_name_kit");
}

function ir_check_or_start_session() {
  if ( ! session_id() ) {
      session_start();
  }
}
// Page Initialization
// Get images array
add_action('init', 'ir_init_action');
function ir_init_action() {
    ir_check_or_start_session();
    // Check parameter in the URL
    $keyword = (
      isset($_SESSION['keyword']) && $_SESSION['keyword'] &&
      (!isset($_GET['keyword']) || (isset($_GET['keyword']) && !$_GET['keyword']))
    ) ?
    sanitize_text_field($_SESSION['keyword']) :
    (
      isset($_GET['keyword']) && $_GET['keyword'] ?
      sanitize_text_field($_GET['keyword']) :
      ''
    );
    $kit = isset($_GET['kit']) ? sanitize_text_field($_GET['kit']) : '';
    if(!empty($kit)){
      // Get the images URLs from the 'images-by-kit' table
      $images = ir_init_function__process_data_from_db('images_by_kit', 'id', $kit);
    }

    if (empty($images) && !empty($keyword)) {
      $_SESSION['keyword'] = $keyword;
      // Get the images URLs from the 'images-by-keyword' table
      $images = ir_init_function__process_data_from_db('images_by_keyword', 'keyword', $keyword);
      if(empty($images)){
          // Get images by API
          $images = ir_init_function__get_data_pexel($keyword);
          if(!empty($images)){
            // Store the extracted image URLs in the 'images-by-keyword' table
            ir_init_function__add_data_to_db('images_by_keyword', [
              'images' => json_encode($images),
              'keyword' => $keyword,
            ]);
          }
        }
    }
    global $IMAGES_global;
    $IMAGES_global = (isset($images) && !empty($images)) ? $images : [];
}

// Get images from database
function ir_init_function__process_data_from_db($table_name, $where_key, $where_value, $images = []) {
  global $wpdb;
  $table = $wpdb->prefix . $table_name;
  $results = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM $table WHERE $where_key = %s", $where_value)
  );
  if(!empty($results)){
    foreach ($results as $result) {
      // Access the individual columns of each row
      $images_array = json_decode($result->images, true);

      // Process the image URLs
      if ($images_array) {
          $images['type'] = $where_key;
          foreach ($images_array as $image) {
            $images[] = $image;
          }
      }
    }
  }
  return $images;
}

// Add new images to database: <images set from pexel> and <keyword>
function ir_init_function__add_data_to_db($table_name, $data = []) {
  global $wpdb;
  $table = $wpdb->prefix . $table_name;
  $wpdb->insert($table, $data);
  return;
}
/*function ir_init_function__get_data_google($keyword) {
  $cx = 'a4499__430e';
  $key = 'AIzaSyC_________si63UU';
  $params = 'cx=' . $cx . '&key=' . $key . '&searchType=image&q=' . $keyword;
  $api_url = 'https://www.googleapis.com/customsearch/v1?' . $params;
  return ir_init_function__send_request('google', $api_url);
}*/

// Get images from pexel
function ir_init_function__get_data_pexel($keyword) {
  $params = 'page=1&per_page=20&query=' . $keyword;
  $api_url = 'https://api.pexels.com/v1/search?' . $params;
  $headers['Authorization'] = 'LASXTfpnmKf1Z__________5TsTt7qigJDYe';
  return ir_init_function__send_request('pexel', $api_url, $headers);
}
function ir_init_function__send_request($api_type, $api_url, $headers = []) {
  if(!$api_type) return;

  $api_response = wp_remote_get(
    $api_url,
    array('timeout' => 10, 'headers' => $headers)
  );

  if (is_wp_error($api_response)) return;

  $response_code = wp_remote_retrieve_response_code($api_response);
  $response_body = wp_remote_retrieve_body($api_response);

  if ($response_code !== 200) return;

  // Parse the response and extract image URLs
  $images_array = json_decode($response_body, true);

  switch ($api_type) {
    /*case 'google':
      return ir_init_function__process_data_google($images_array);*/
    case 'pexel':
      return ir_init_function__process_data_pexel($images_array);
  }
}
/*function ir_init_function__process_data_google($data) {
  if(!isset($data['items'])) return;
  foreach ($data['items'] as $image) {
    if(!isset($image['link'])) continue;
    $image['link'] = explode('?', $image['link'])[0];
    $images[] = $image['link'];
  }
  return $images;
}*/
function ir_init_function__process_data_pexel($data) {
  if(!isset($data['photos'])) return;
  foreach ($data['photos'] as $i => $image) {
    if($i === 0){
      if(!isset($image['src']['large2x'])) continue;
      $images[] = $image['src']['large2x'];
    } else {
      if(!isset($image['src']['large'])) continue;
      $images[] = $image['src']['large'];
    }
  }
  return $images;
}

// Page Content
// Replace images links
// add_filter('wp_get_attachment_url', 'ir_get_attachment_url_filter');
// function ir_get_images($url) {
//   global $IMAGES_global;
//   if(empty($IMAGES_global)) return $url;

//   $type = isset($IMAGES_global['type']) ? $IMAGES_global['type'] : 'kit';
//   unset($IMAGES_global['type']);

//   if($type === 'keyword'){
//     $rand_key = array_rand($IMAGES_global, 1);
//     $url = $IMAGES_global[$rand_key];

//     global $IMAGES_global__kit;
//     $IMAGES_global__kit[] = $url;
//     unset($IMAGES_global[$rand_key]);
//     return $url;
//   } elseif($type === 'kit'){
//     $url = $IMAGES_global[0];
//     unset($IMAGES_global[0]);
//     return $url;
//   } else {
//     return $url;
//   }
// }

// Store the image kit URLs in the 'images-by-kit' table
add_action( 'wp_head', 'ir_head_action' );
function ir_head_action(){
  global $IMAGES_global;
  if(empty($IMAGES_global)) return;

  $type = isset($IMAGES_global['type']) ? $IMAGES_global['type'] : 'kit';
  unset($IMAGES_global['type']);
  ?>
  <script>
      let IMAGES_global = <?=json_encode($IMAGES_global);?>;

      const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
          Array.from(mutation.addedNodes).forEach(node => {
            if (node.tagName === 'IMG' && node.width > 200 && !node.classList.contains('pp-image')) {
              let image = IMAGES_global.shift();
              if(image != null){
                node.src = image;
                if(node.srcset != null){
                  node.srcset = image;
                }
              }
            }else if(node.tagName === 'FIGURE' && node.style.backgroundImage.length > 1){
                let image_bg = IMAGES_global.shift();
                if(image_bg != null){
                  node.style.backgroundImage = `url(${image_bg})`;
                }
              }
          });
        });
      });
      observer.observe(document.documentElement, {
        childList: true,
        subtree: true
      });
  </script>
  <?php
}