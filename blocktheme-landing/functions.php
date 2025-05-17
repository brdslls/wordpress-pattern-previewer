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
    /* header */
    .wp-block-navigation a[href="#"]{pointer-events:none}
    header.wp-block-template-part p:first-child{line-height:1;width:max-content;gap:var(--wp--preset--spacing--small);display:flex;align-items:center}
    @media(max-width:599px){
        header.wp-block-template-part>.wp-block-group>.wp-block-group>.wp-block-group:first-child .wp-block-navigation__responsive-container-open{background:center / contain no-repeat url(' .get_template_directory_uri() . 'images/sprite.svg#phone)}
        header.wp-block-template-part>.wp-block-group>.wp-block-group>.wp-block-group:first-child .wp-block-navigation__responsive-container-open svg{opacity:0}
        /* aligning */
        header.wp-block-template-part .wp-block-group:first-child p:first-child{width:min-content;font-size:var(--wp--preset--font-size--small)}
        .wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation__responsive-container-content,
        .wp-block-navigation__responsive-container.is-menu-open .wp-block-navigation__responsive-container-content .wp-block-navigation__container{align-items:center!important}
        /* spacing */
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
    /* if browser supports :has() overwrite default grid view with horizontally scrolled */
    @media(max-width:599px){
        .wp-block-group-is-layout-grid:has(.wp-block-image:nth-child(4):last-child){overflow:scroll;scroll-snap-type:x mandatory;grid-template-columns:repeat(4, minmax(min(20rem, 100%), 1fr))}
        .wp-block-group-is-layout-grid .wp-block-image{scroll-snap-align:center}
    }
    /* if browser supports :has() overwrite default grid auto-sizing (auto-fill) with auto-fit */
    @media(min-width:600px){
        .wp-block-group-is-layout-grid:has(:nth-child(4)):not(:has(:nth-child(5))){grid-template-columns:repeat(auto-fit, minmax(min(10rem, 100%), 1fr))}
    }
    /* shadow on hover */
    .wp-block-media-text.has-background:hover{box-shadow:var(--wp--preset--shadow--base)}
    </style>';
});
/* cover: video */
add_action('wp_footer', function(){
    echo '<style>
        header + .wp-block-group .wp-block-cover{pointer-events:none}
        .wp-block-cover__video-background, header + .wp-block-group .wp-block-cover a{pointer-events:all}
        .wp-block-media-text.has-media-on-the-top{grid-template-columns:1fr;}.wp-block-media-text.has-media-on-the-top>.wp-block-media-text__content{grid-column:1;grid-row:2}
    </style>
    <script>
        let video = document.querySelector(".wp-block-cover__video-background");
        if(null!=video){video.addEventListener("click", ()=>{video.paused ? video.play() : video.pause()})}
    </script>';
});
/* styles: font */
add_action('wp_head', function(){
    echo '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Wix+Madefor&display=swap" rel="stylesheet">';
});