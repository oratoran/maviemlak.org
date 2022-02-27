<?php
class ImageHandling
{
  function __construct()
  {

    add_action('after_setup_theme', [$this, 'add_blurred_size']);
    add_action('graphql_register_types', [$this, 'handle_graphql']);
    add_filter('wp_generate_attachment_metadata', [$this, 'filter_wp_generate_attachment_metadata'], 10, 2);
  }

  function add_blurred_size()
  {
    add_image_size("blurred-preview", 16);
  }

  function handle_graphql()
  {
    register_graphql_field('MediaItem', 'blurredPreview', [
      'type' => 'String',
      'description' => "Base64 encoded preview",
      'resolve' => function (\WPGraphQL\Model\Post $post, $args, $context, $info) {
        $base64 = get_post_meta($post->ID, 'base64');
        if ($base64) {
          return $base64[0];
        }
        return null;
      }
    ]);
  }

  function filter_wp_generate_attachment_metadata($metadata, $attachment_id)
  {
    if (isset($metadata["sizes"]["blurred-preview"])) {
      $src = wp_get_attachment_image_src($attachment_id, "blurred-preview");
      $base64 = base64_encode(file_get_contents($src[0]));
      update_post_meta($attachment_id, 'base64', $base64);
    }
    return $metadata;
  }
}
