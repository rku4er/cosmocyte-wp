<?php

namespace Roots\Sage\Utils;

/**
 * Tell WordPress to use searchform.php from the templates/ directory
 */
function get_search_form() {
  $form = '';
  locate_template('/templates/searchform.php', true, false);
  return $form;
}
add_filter('get_search_form', __NAMESPACE__ . '\\get_search_form');

/**
 * Check if element is empty
 */
function is_element_empty($element) {
  $element = trim($element);
  return !empty($element);
}

/**
 * Make a URL relative
 */
function root_relative_url($input) {
  $url = parse_url($input);
  if (!isset($url['host']) || !isset($url['path'])) {
    return $input;
  }
  $site_url = parse_url(network_site_url());  // falls back to site_url

  if (!isset($url['scheme'])) {
    $url['scheme'] = $site_url['scheme'];
  }
  $hosts_match = $site_url['host'] === $url['host'];
  $schemes_match = $site_url['scheme'] === $url['scheme'];
  $ports_exist = isset($site_url['port']) && isset($url['port']);
  $ports_match = ($ports_exist) ? $site_url['port'] === $url['port'] : true;

  if ($hosts_match && $schemes_match && $ports_match) {
    return wp_make_link_relative($input);
  }
  return $input;
}

/**
 * Compare URL against relative URL
 */
function url_compare($url, $rel) {
  $url = trailingslashit($url);
  $rel = trailingslashit($rel);
  return ((strcasecmp($url, $rel) === 0) || root_relative_url($url) == $rel);
}

/**
 * Hooks a single callback to multiple tags
 */
function add_filters($tags, $function, $priority = 10, $accepted_args = 1) {
  foreach ((array) $tags as $tag) {
    add_filter($tag, $function, $priority, $accepted_args);
  }
}

/**
 * Display container class name
 */
function get_container_class($template = 'template-fullwidth.php'){
     return is_page_template($template) ? 'container-fluid' : 'container';;
}

/**
 * We need to be able to figure out the attributes of a wrapped shortcode
 */
function attribute_map($str, $att = null) {
    $res = array();
    $return = array();
    $reg = get_shortcode_regex();
    preg_match_all('~'.$reg.'~',$str, $matches);
    foreach($matches[2] as $key => $name) {
        $parsed = shortcode_parse_atts($matches[3][$key]);
        $parsed = is_array($parsed) ? $parsed : array();

            $res[$name] = $parsed;
            $return[] = $res;
        }
    return $return;
}
