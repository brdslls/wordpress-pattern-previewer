<?php
/*
Plugin Name: Block Replacer
Plugin URI: https://github.com/brdslls/wordpress-pattern-previewer
Description: Allow to show different variations of blocks in public side of website based on GET params
Author: brdslls
Version: 0.0.1
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

add_filter( 'block_parser_class', 'pb_block_parser_class_filter', 11, 1 );
function pb_block_parser_class_filter( $default_parser_class ) {
    return 'CustomParser';
}

class CustomParser extends WP_Block_Parser {
  private $PREFIX = 'blocktheme';

  public static $BLOCKS = array(
    'banner',
    'header',
    // about, advantages, cta, footer, loop, partners, projects, reviews, services, team, etc
  );

  public function parse( $document ) {
    $document = $this->replaceBlocks($document);

		$this->document    = $document;
		$this->offset      = 0;
		$this->output      = array();
		$this->stack       = array();
		$this->empty_attrs = json_decode( '{}', true );

		while ( $this->proceed() ) {
			continue;
		}
		return $this->output;
	}

  private function replaceBlocks($document){
    $from = array();
    $to = array();

    foreach (self::$BLOCKS as $block) {
      $blockNew = $this->checkBlockParam($block);
      if(!$blockNew) continue;

      $from[] = $this->PREFIX . '/' . $block . '-1';
      $to[] = $this->PREFIX . '/' . $blockNew;
    }


    return str_replace($from, $to, $document);
  }

  private function checkBlockParam($block){
    if(!isset($_SESSION[$block]) && !isset($_GET[$block])) return false;

    $blockValue = (
      isset($_SESSION[$block]) && $_SESSION[$block] &&
      (!isset($_GET[$block]) || (isset($_GET[$block]) && !$_GET[$block]))
    ) ?
    sanitize_text_field($_SESSION[$block]) :
    (
      isset($_GET[$block]) && $_GET[$block] ?
      sanitize_text_field($_GET[$block]) :
      ''
    );
    if(!$blockValue) return false;

    // create block name: "blockname-<num>". Real block name always "blockname-1" (in .html page template for current theme)
    $blockNew = $block . '-' . preg_replace('/[^0-9]/', '', $blockValue);

    $patternPath = get_template_directory() . '/patterns/' . $blockNew . '.php';
    if(!file_exists($patternPath)) return false;
    return $blockNew;
  }
}

// save user choice
add_action('init', 'pb_init_action');
function pb_init_action() {
  pb_check_or_start_session();
  $BLOCKS = CustomParser::$BLOCKS;
  foreach ($BLOCKS as $blockName) {
    if(isset($_GET[$blockName]) && $_GET[$blockName]){
      $_SESSION[$blockName] = sanitize_text_field($_GET[$blockName]);
    }
  }
}

function pb_check_or_start_session() {
  if ( ! session_id() ) {
      session_start();
  }
}