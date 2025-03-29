<?php
/*
Plugin Name: Preset previewer
Plugin URI: https://github.com/brdslls/wordpress-pattern-previewer
Description: Allow to preview different variations of blocks, font, color and etc in Block Theme
Author: brdslls
Version: 0.0.1
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

class PresetPreviewer {
  private $PARTS;

  public function __construct($PARTS) {
    $this->PARTS = $PARTS;
  }

  public function pp_wp_enqueue_scripts_action(){
    wp_enqueue_style('pp_style', plugin_dir_url(__FILE__) . 'style.css', array(), '0.0.1');
    wp_enqueue_script('pp_script', plugin_dir_url(__FILE__) . 'script.js', array(), '0.0.1', true);
  }

  public function pp_wp_footer_action(){ ?>
    <div class="pp-holder pp-holder--trigger pp-holder--open">
      <div class="pp-wrap">
        <div class="pp-buttons">
          <button class="pp-button pp-button--svg pp-button--setting" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" class="pp-icon" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/></svg></button>
        </div>
      </div>
    </div>
    <div class="pp-holder pp-holder--main">
      <div class="pp-wrap">
        <div class="pp-buttons pp-buttons--close">
          <button class="pp-button pp-button--svg pp-button--close" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" class="pp-icon" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg></button>
        </div>
        <div class="pp-subtext"><h4>Blocks variations</h4><small>choose your business type and click "Apply"</small></div>
        <form class="pp-form" action="" method="get">
          <div class="pp-buttons pp-buttons--form pp-buttons--top">
            <button class="pp-button pp-button--accept" type="submit">Apply</button>
          </div>
          <div class="pp-input_wrap">
            <label class="pp-label" for="keyword">
              <select name="keyword" id="keyword" style="border:1px solid">
                <option value>Choose your business type</option>
                <?php foreach ([
                  'business' => 'Business',
                  'real estate' => 'Real Estate',
                  'education' => 'Education',
                  'retail' => 'Retail',
                  'weddings' => 'Weddings',
                  'cake' => 'Cake',
                  ] as $themeName => $themeLabel) { ?>
                  <option value="<?php echo $themeName; ?>" <?php echo (isset($_GET['keyword']) && $_GET['keyword'] === strval($themeName)) ? ' selected' : ''; ?>><?php echo $themeLabel; ?></option>
                <?php } ?>
              </select>
            </label>
          </div>
          <div class="pp-buttons pp-buttons--form pp-buttons--top">
          </div>
          <?php foreach ($this->PARTS as $patternName => $pattern) { ?>
            <fieldset class="pp-fieldset">
              <legend class="pp-legend"><?php echo $pattern[0]; ?></legend>
              <?php for ($patternVariant=1; $patternVariant <= $pattern[1]; $patternVariant++) { ?>
                <div class="pp-input_wrap">
                <label class="pp-label" for="<?php echo $patternName . $patternVariant; ?>">
                  <input id="<?php echo $patternName . $patternVariant; ?>" class="pp-input visually-hidden" type="radio" name="<?php echo $patternName; ?>" value="<?php echo $patternVariant; ?>" <?php echo (isset($_GET[$patternName]) && $_GET[$patternName] === strval($patternVariant)) ? ' checked' : ''; ?>>
                  <span class="pp-input-text visually-hidden"><?php echo ucfirst($patternName) . ' ' . $patternVariant; ?></span>
                  <div class="pp-image_wrap">
                    <img class="pp-image" src="/wp-content/plugins/preset-previewer/images/<?php echo $patternName .'-'. $patternVariant; ?>.png" title="" loading="lazy">
                  </div>
                </label>
              </div>
              <?php } ?>
            </fieldset>
            <div class="pp-buttons pp-buttons--form pp-buttons--top">
              <button class="pp-button" type="submit">Apply</button>
            </div>
            <?php } ?>
            <div class="pp-buttons pp-buttons--form pp-buttons--bottom">
            <button class="pp-button pp-button--accept" type="submit">Apply</button>
            </div>
          </form>
        </div>
      </div>
  <?php }
}

add_action('wp', 'run_preset_previewer');
function run_preset_previewer() {
  // name => [Title, number of variations]
  $PARTS = array(
    'header' => ['Header', 1],
    'banner' => ['Banner', 2],
    // about, advantages, cta, footer, loop, partners, projects, reviews, services, team, etc
  );
  if (!is_front_page()){
    $PARTS = array(
      'header' => $PARTS['header'],
      'footer' => $PARTS['footer'],
    );
  }

  $plugin = new PresetPreviewer($PARTS);

  add_action('wp_enqueue_scripts', array($plugin, 'pp_wp_enqueue_scripts_action'));
  add_action('wp_footer', array($plugin, 'pp_wp_footer_action'));
}