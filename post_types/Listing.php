<?php
class Listing
{
  function __construct()
  {
    add_action('init', [$this, 'register'], 0);
    add_action('save_post_listing', [$this, 'save']);
    add_action('graphql_register_types', [$this, 'init_graphql']);
    add_filter('graphql_post_object_connection_query_args', [$this, 'register_custom_graphql_where'], 10, 5);
  }

  function register()
  {

    $labels = array(
      'name'                  => _x('Listings', 'Post Type General Name', 'text_domain'),
      'singular_name'         => _x('Listing', 'Post Type Singular Name', 'text_domain'),
      'menu_name'             => __('Listings', 'text_domain'),
      'name_admin_bar'        => __('Listing', 'text_domain'),
      'archives'              => __('Item Archives', 'text_domain'),
      'attributes'            => __('Item Attributes', 'text_domain'),
      'parent_item_colon'     => __('Parent Item:', 'text_domain'),
      'all_items'             => __('All Items', 'text_domain'),
      'add_new_item'          => __('Add New Item', 'text_domain'),
      'add_new'               => __('Add New', 'text_domain'),
      'new_item'              => __('New Item', 'text_domain'),
      'edit_item'             => __('Edit Item', 'text_domain'),
      'update_item'           => __('Update Item', 'text_domain'),
      'view_item'             => __('View Item', 'text_domain'),
      'view_items'            => __('View Items', 'text_domain'),
      'search_items'          => __('Search Item', 'text_domain'),
      'not_found'             => __('Not found', 'text_domain'),
      'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
      'featured_image'        => __('Featured Image', 'text_domain'),
      'set_featured_image'    => __('Set featured image', 'text_domain'),
      'remove_featured_image' => __('Remove featured image', 'text_domain'),
      'use_featured_image'    => __('Use as featured image', 'text_domain'),
      'insert_into_item'      => __('Insert into item', 'text_domain'),
      'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
      'items_list'            => __('Items list', 'text_domain'),
      'items_list_navigation' => __('Items list navigation', 'text_domain'),
      'filter_items_list'     => __('Filter items list', 'text_domain'),
    );
    $args = array(
      'label'                 => __('Listing', 'text_domain'),
      'description'           => __('Property listings', 'text_domain'),
      'labels'                => $labels,
      'supports'              => array('title', 'editor', 'thumbnail', 'trackbacks', 'revisions', 'custom-fields'),
      'taxonomies'            => array('category', 'post_tag'),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'page',
      'show_in_rest'          => true,
      'show_in_graphql'       => true,
      'graphql_single_name'   => 'listing',
      'graphql_plural_name'    => 'allListing',
    );
    register_post_type('listing', $args);
  }

  function init_graphql()
  {

    register_graphql_field(
      'Listing',
      'propertyImages',
      [
        'description' => "Images",
        'type'        => ['list_of' => 'MediaItem'],
        'resolve'     => function ($root, $args, $context) {
          $gallery_data = json_decode(get_post_meta($root->ID, '_gallery_data', true));

          if (json_last_error() === JSON_ERROR_NONE) {
            $image_data = array_map(function ($item) use ($context) {
              return $item->id;
            }, $gallery_data);

            return $context->get_loader('post')->load_many($image_data);
          } else {
            return [];
          }
        }
      ]
    );

    register_graphql_object_type('OtherPropertyField', [
      'description' => '',
      'fields' => [
        'key' => [
          'type' => 'String'
        ],
        'value' => [
          'type' => 'String'
        ]
      ]
    ]);

    register_graphql_field(
      'Listing',
      'otherProperties',
      [
        'description' => "Other Properties",
        'type'        => ['list_of' => 'OtherPropertyField'],
        'resolve'     => function ($root, $args, $context) {
          $data = json_decode(get_post_meta($root->ID, '_repeater_text_data', true));
          if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
          } else {
            return [];
          }
        }
      ]
    );

    register_graphql_field('RootQueryToListingConnectionWhereArgs', 'propertyType', [
      'type' => 'String',
    ]);
  }

  function register_custom_graphql_where($query_args, $source, $args, $context, $info)
  {
    $propertyType = $args['where']['propertyType'];

    if (isset($propertyType)) {
      $query_args['meta_query'] = [
        [
          'key' => 'propertyType',
          'value' => $propertyType,
          'compare' => '='
        ]
      ];
    }

    return $query_args;
  }


  function save($post_id)
  {
    if (isset($_POST['_gallery_data'])) {
      $gallery_data = stripslashes($_POST['_gallery_data']);
      $gallery_data = json_decode($gallery_data);
      if (json_last_error() === JSON_ERROR_NONE) {
        update_post_meta($post_id, '_gallery_data', json_encode($gallery_data));
      }
    }
  }
}
