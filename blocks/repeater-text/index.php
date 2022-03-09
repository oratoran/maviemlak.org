<?php
class RepeaterText
{
  function __construct($screens, $title = "Repeater text")
  {
    $this->screens = $screens;
    $this->title = $title;
    add_action('add_meta_boxes', [$this, 'add_metabox']);
    add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

    if(is_array($screens)) {
      foreach($screens as $screen) {
        add_action('save_post_'.$screen, [$this, 'save']);
      }
    } else {
      add_action('save_post_'.$screens, [$this, 'save']);
    }
  }

  function add_metabox()
  {
    add_meta_box(
      'custom_repeater_text',
      $this->title,
      [$this, 'display_meta_box'],
      $this->screens
    );
  }

  function enqueue_scripts()
  {
    $asset_file = require(__DIR__ . "/build/index.asset.php");

    wp_enqueue_script('wp-api');

    wp_enqueue_script(
      'cmb-repeater-text-block',
      get_template_directory_uri() . "/blocks/repeater-text/build/index.js",
      $asset_file['dependencies'],
      $asset_file['version'],
      true
    );
  }

  function display_meta_box($post)
  {
    $data =  get_post_meta($post->ID, '_repeater_text_data');
    if ($data && count($data) === 1) {
      echo "<script>var _REPEATER_TEXT_DATA = " . $data[0] . "; </script>";
    } else {
      echo "<script>var _REPEATER_TEXT_DATA = [{}]</script>";
    }
    echo "<div id='custom-metabox-repeatertext'></div>";
  }

  function save($post_id) {
    write_log( $_POST);
    if (isset($_POST['_repeater_text_data'])) {
      $data = stripslashes($_POST['_repeater_text_data']);
      $data = json_decode($data);
      if (json_last_error() === JSON_ERROR_NONE) {
        update_post_meta($post_id, '_repeater_text_data', json_encode($data));
      }
    }
  }
}
