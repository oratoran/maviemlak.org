<?php

require_once __DIR__ . "./post_types/Listing.php";
require_once __DIR__ . "./blocks/gallery/index.php";
require_once __DIR__ . "./blocks/repeater-text/index.php";

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

new GalleryBlock(["listing"]);
new RepeaterText(["listing"], "Other Properties");
new Listing();