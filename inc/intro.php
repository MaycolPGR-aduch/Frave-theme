<?php
/**
 * Entrance intro: the first page a visitor opens (again after 12 hours) shows the brand
 * orange with the white wordmark, which lifts like a curtain once the page has loaded (at
 * least 1.4 s so it reads, at most 3.5 s so it never holds the site back). A click also
 * lifts it.
 *
 * Its style and script are inline in <head> and right after <body>, so it shows before the
 * theme's stylesheet arrives. It shows again after 12 hours (localStorage, never a cookie),
 * and its wordmark is the header's own file turned white (preloaded, as the header needs it
 * anyway), so it adds no download.
 * Automated browsers (navigator.webdriver) skip it; turn it off with the filter
 * frave_show_intro. With reduced motion it fades instead of moving.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Whether pages may open with the intro. */
function frave_show_intro(): bool {
	return (bool) apply_filters( 'frave_show_intro', ! is_admin() && ! is_customize_preview() && ! wp_is_json_request() );
}

/** Intro style, and the decision (first page in 12 hours) before anything is painted. */
function frave_intro_head(): void {
	if ( ! frave_show_intro() ) {
		return;
	}
	?>
	<style id="frave-intro-css">
		.site-intro{display:none}
		html.site-intro-on{overflow:hidden}
		html.site-intro-on .site-intro{position:fixed;z-index:2147483000;inset:0;display:grid;place-items:center;align-content:center;gap:1.6rem;background:radial-gradient(circle at 50% 45%,#ff6d22 0,#fc4c02 58%,#ee4700 100%);cursor:pointer;transition:transform .75s cubic-bezier(.7,0,.2,1),opacity .5s ease .2s}
		html.site-intro-out .site-intro{transform:translateY(-100%);opacity:0;pointer-events:none}
		.site-intro__logo{display:block;width:min(62vw,21rem);height:auto;filter:brightness(0) invert(1);opacity:0}
		.site-intro__logo.is-loaded{opacity:1;animation:frave-intro-in .9s cubic-bezier(.2,.7,.2,1) both}
		.site-intro__bar{width:min(30vw,7rem);height:2px;border-radius:2px;background:rgb(255 255 255/35%);overflow:hidden}
		.site-intro__bar::after{content:"";display:block;width:100%;height:100%;background:#fff;transform:scaleX(0);transform-origin:left;animation:frave-intro-bar 1.4s cubic-bezier(.4,0,.2,1) .2s forwards}
		@keyframes frave-intro-in{from{opacity:0;transform:translateY(14px) scale(.96)}}
		@keyframes frave-intro-bar{to{transform:scaleX(1)}}
		@media (prefers-reduced-motion:reduce){.site-intro__logo.is-loaded,.site-intro__bar::after{animation:none}.site-intro__bar::after{transform:none}html.site-intro-on .site-intro{transition:opacity .4s ease}html.site-intro-out .site-intro{transform:none}}
	</style>
	<link rel="preload" as="image" href="<?php echo esc_url( frave_asset_url( 'assets/images/frave-wordmark.webp' ) ); ?>" fetchpriority="high">
	<script id="frave-intro-js">(function(){try{var now=Date.now(),seen=+localStorage.getItem('frave-intro')||0;if(navigator.webdriver||now-seen<432e5)return;localStorage.setItem('frave-intro',String(now));document.documentElement.classList.add('site-intro-on');}catch(e){}})();</script>
	<?php
}
add_action( 'wp_head', 'frave_intro_head', 1 );

/**
 * The curtain itself, first thing in <body>, and what lifts it. Decorative: hidden from
 * assistive technology. Its wordmark is the header's file, so it is downloaded only once.
 */
function frave_intro_markup(): void {
	if ( ! frave_show_intro() ) {
		return;
	}
	?>
	<div class="site-intro" aria-hidden="true" data-site-intro>
		<img class="site-intro__logo" src="<?php echo esc_url( frave_asset_url( 'assets/images/frave-wordmark.webp' ) ); ?>" alt="" width="720" height="240" fetchpriority="high" onload="this.classList.add('is-loaded')">
		<div class="site-intro__bar"></div>
	</div>
	<script>(function(){var root=document.documentElement;if(!root.classList.contains('site-intro-on'))return;var intro=document.querySelector('[data-site-intro]'),start=Date.now(),done=false;function lift(){if(done)return;done=true;setTimeout(function(){root.classList.add('site-intro-out');setTimeout(function(){root.classList.remove('site-intro-on','site-intro-out');},800);},Math.max(0,1400-(Date.now()-start)));}if(document.readyState==='complete')lift();else window.addEventListener('load',lift);setTimeout(lift,3500);intro.addEventListener('click',lift);})();</script>
	<?php
}
add_action( 'wp_body_open', 'frave_intro_markup', 1 );
