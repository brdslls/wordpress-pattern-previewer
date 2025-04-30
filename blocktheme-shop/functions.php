<?php
/* heading block: replace h1-h6 html tags with p */
add_filter('render_block_core/post-title', function($block_content, $block){
    $block_content = preg_replace('/<h[2-6](.*?)>/', '<p$1>', $block_content);
    $block_content = preg_replace('/<\/h[2-6]>/', '</p>', $block_content);
    return $block_content;
}, 10, 2 );
/* comments form block: remove title */
add_filter( 'comment_form_defaults', function($defaults){
	$defaults['title_reply'] = '';
	return $defaults;
}, 10, 1 );
/* comments form block: remove website field */
add_filter( 'comment_form_fields', function($comment_fields){
	unset($comment_fields['url']);
	return array_reverse($comment_fields);
}, 10, 1 );
/* styles: fix layout */
add_action('wp_head', function(){
    echo '<style>
    /* root section */
    header.wp-block-template-part > .wp-block-group{padding:var(--wp--preset--spacing--small) var(--wp--preset--spacing--medium)}
    footer.wp-block-template-part > .wp-block-group{padding:var(--wp--preset--spacing--medium)}
    .wp-site-blocks > .wp-block-group{margin:var(--wp--preset--spacing--x-large) var(--wp--preset--spacing--medium)}
    /* header menu */
    .wp-block-navigation a[href="#"]{pointer-events:none}
    @media(max-width:599px){
        .wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation__responsive-dialog{margin:var(--wp--preset--spacing--large)}
        .wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation-item{gap:inherit}
        .wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation__responsive-container-content .wp-block-navigation__submenu-container{padding-top:0}
    }
    @media(min-width:600px){
        /* first level menu */
        .wp-block-navigation > .wp-block-navigation-item > .wp-block-navigation-submenu{padding-block-start:var(--wp--preset--spacing--medium);border:none}
        .wp-block-navigation > .wp-block-navigation-item > .wp-block-navigation-submenu > .wp-block-navigation-item{border:1px solid var(--wp--preset--color--contrast-pale)}
        .wp-block-navigation > .wp-block-navigation-item > .wp-block-navigation-submenu > .wp-block-navigation-item:not(:first-child){border-top:none}
        .wp-block-navigation > .wp-block-navigation-item > .wp-block-navigation-submenu > .wp-block-navigation-item:not(:last-child){border-bottom:none}
    }
    /* search */
    .wp-block-search__button-only .wp-block-search__input{min-width:auto}
    @media(max-width:599px){
        .wp-block-search__button-only .wp-block-search__input{position:absolute;top:100%;right:0;padding:16px;z-index:2}
    }
    /* product */
    #email, #author{display:block;width:100%}
    /* product loop */
    .wc-block-product{display:grid;grid-template-rows:auto 1fr auto auto;border:1px solid var(--wp--preset--color--primary-pale)}
    .attachment-woocommerce_thumbnail{aspect-ratio:1/1}
    .wp-block-button.wc-block-components-product-button .wc-block-components-product-button__button{width:100%}
    .wp-block-button.wc-block-components-product-button{gap:0} .wp-block-button a.added_to_cart{display:none}
    /* if browser supports :has() overwrite default grid view with horizontally scrolled */
    @media(max-width:599px){
        .wp-block-group-is-layout-grid:has(.wp-block-pullquote:nth-child(6):last-child){overflow:scroll;scroll-snap-type:x mandatory;grid-template-columns:repeat(6, minmax(min(20rem, 100%), 1fr))}
        .wp-block-group-is-layout-grid .wp-block-pullquote{scroll-snap-align:center}
    }
    /* if browser supports :has() overwrite default grid auto-sizing (auto-fill) with auto-fit */
    @media(min-width:600px){
        .wp-block-group-is-layout-grid:has(:nth-child(4):last-child){grid-template-columns:repeat(auto-fit, minmax(min(10rem, 100%), 1fr))}
    }
    </style>';
});
/* scripts: fix layout warnings */
add_action('wp_footer', function(){
    if(!wp_is_mobile() && (is_shop() || is_product_taxonomy())){
        echo '<script>
        let sidebarDetails = document.querySelector(".wp-block-template-part .wp-block-details");
        if(null!=sidebarDetails){sidebarDetails.open = true}
        </script>';
    }
});