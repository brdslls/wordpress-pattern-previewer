<?php
/**
 * Title: banner 1
 * Slug: blocktheme/banner-1
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0"><!-- wp:cover {"url":"<?=get_template_directory_uri()?>/video/placeholder.mp4","id":92,"overlayColor":"contrast-pale","isUserOverlayColor":true,"backgroundType":"video","sizeSlug":"full","align":"wide","style":{"border":{"radius":{"topLeft":"1rem","topRight":"1rem"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignwide" style="border-top-left-radius:1rem;border-top-right-radius:1rem"><video class="wp-block-cover__video-background intrinsic-ignore" autoplay muted loop playsinline src="<?=get_template_directory_uri()?>/video/placeholder.mp4" data-object-fit="cover"></video><span aria-hidden="true" class="wp-block-cover__background has-contrast-pale-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large"},"blockGap":"var:preset|spacing|x-large"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large)"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"placeholder":"Write title…","fontSize":"x-large"} -->
<p class="has-x-large-font-size"><strong>LOREM IPSUM DOLOR SIT AMET</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"placeholder":"Write title…","fontSize":"large"} -->
<p class="has-large-font-size">Lorem ipsum dolor sit amet, consectetur adipiscing elit.<br>Nullam dapibus blandit enim, ac malesuada justo euismod id.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"layout":{"columnSpan":1,"rowSpan":1}}} -->
<p><a href="#"><img style="width: 64px;filter: invert(1);" src="<?=get_template_directory_uri()?>/images/sprite.svg#video" alt="video"></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover --></div>
<!-- /wp:group -->