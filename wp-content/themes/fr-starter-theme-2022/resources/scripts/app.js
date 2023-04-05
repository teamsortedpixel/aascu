import {domReady} from '@roots/sage/client';

// 1. Import 3rd Party Libs
import './autoload/_bootstrap';
import './autoload/_fonts';
import fetchInject from 'fetch-inject';
window.fetchInject = fetchInject;

import StickySidebar from 'sticky-sidebar';
window.StickySidebar = StickySidebar;

// 2. Import custom JS
import './util/current-breakpoint';
import './util/load-splide-async';
import './util/get-filters-form-url-params';
import './util/load-truncate-ellipsis-async';
import './util/jq-initialize';
import './util/truncate-text';
import './util/fr-helper';
import './util/load-simple-scrollbar-js-async';
import './util/load-panzoom-async';
import './util/element-is-in-viewport';
import './util/load-choices-async';
import './global/header';
import './components/search-bar';
import './components/nav-search-button';
import './components/search-results-container';
import './components/card';
import './components/update-url-params';
import './components/side-nav-quick-links';
import './components/embedded-video-thumbnail';
import './components/custom-dropdowns';
import './components/tables';
import './components/go-to-anchor';
import './components/person-verify-recaptcha';
import './blocks/member-spotlight';
import './blocks/card-grid';
import './blocks/filterable-content';
import './blocks/network-map';
import './blocks/featured-opportunities';
import './blocks/upcoming-opportunities';
import './blocks/testimonial-slider';
import './blocks/partner-grid';

//Make variables globals
window.currentBreakpoint = require('./util/current-breakpoint').currentBreakpoint;

// import then needed Font Awesome functionality
import { library, dom } from '@fortawesome/fontawesome-svg-core';

// import the Facebook and Twitter icons
import {
  faChevronDown,
  faSearch,
  faBars,
  faXmark
} from '@fortawesome/free-solid-svg-icons';

// add the imported icons to the library
library.add(
  faSearch,
  faChevronDown,
  faBars,
  faXmark
);

// tell FontAwesome to watch the DOM and add the SVGs when it detects icon markup
dom.watch();

/**
 * app.main
 */
const main = async (err) => {
  if (err) {
    // handle hmr errors
    console.error(err);
  }

  // application code
};

/**
 * Initialize
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
domReady(main);
import.meta.webpackHot?.accept(main);
