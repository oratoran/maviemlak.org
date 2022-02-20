<?php
class GalleryBlock
{
  function __construct($screens)
  {
    $this->screens = $screens;
    add_action('add_meta_boxes', [$this, 'add_metabox']);
    add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
  }

  function add_metabox()
  {
    add_meta_box(
      'custom_gallery',
      'Gallery',
      [$this, 'display_meta_box'],
      $this->screens
    );
  }

  function enqueue_scripts()
  {
    $asset_file = require(__DIR__ . "/build/index.asset.php");

    wp_enqueue_script('wp-api');

    wp_enqueue_script(
      'cmb-gallery-block',
      get_template_directory_uri() . "/blocks/gallery/build/index.js",
      $asset_file['dependencies'],
      $asset_file['version'],
      true
    );
  }

  function display_meta_box($post)
  {
    $gallery_data =  get_post_meta($post->ID, '_gallery_data');
    if ($gallery_data && count($gallery_data) === 1) {
      echo "<script>var GALLERY_DATA = " . $gallery_data[0] . "; </script>";
    } else {
      echo "<script>var GALLERY_DATA = []</script>";
    }
    echo "<div id='custom-metabox-gallery'></div>";
  }
}
