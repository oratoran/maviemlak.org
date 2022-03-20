<?php

if (!function_exists('write_log')) {

  function write_log($log)
  {
    if (true === WP_DEBUG) {
      if (is_array($log) || is_object($log)) {
        error_log(print_r($log, true));
      } else {
        error_log($log);
      }
    }
  }
}

require_once __DIR__ . "./post_types/Listing.php";
require_once __DIR__ . "./post_types/SiteSettings.php";
require_once __DIR__ . "./post_types/Agents.php";
require_once __DIR__ . "./post_types/Locations.php";
require_once __DIR__ . "./post_types/News.php";
require_once __DIR__ . "./post_types/Testimonials.php";

require_once __DIR__ . "./blocks/gallery/index.php";
require_once __DIR__ . "./blocks/repeater-text/index.php";
require_once __DIR__ . "./helpers/ImageHandling.php";

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

new GalleryBlock(["listing"]);
new RepeaterText(["listing"], "Other Properties");

new SiteSettings();
new Listing();
new Locations();
new Agents();
new News();
new Testimonials();


new ImageHandling();