import {domReady} from '@roots/sage/client';
import {registerBlockStyle, unregisterBlockStyle} from '@wordpress/blocks';

import './util/closest-child-editor';
import './util/wp-editor';
import './autoload/_fonts';
import './components/empty-block--editor';
import './components/only-one-inner-block--editor';
import './blocks/wysiwyg-module--editor';
import './blocks/fr-page-builder--editor';
import './blocks/layout-column--editor';
import './blocks/layout-module--editor';
import './blocks/content-section--editor';
import './blocks/content-tiles--editor';
import './blocks/tab-panels--editor';
import './blocks/weave-slider--editor';
import './blocks/partner-grid--editor';

/**
 * editor.main
 */
const main = (err) => {
  if (err) {
    // handle hmr errors
    console.error(err);
  }

  unregisterBlockStyle('core/button', 'outline');

  registerBlockStyle('core/button', {
    name: 'outline',
    label: 'Outline',
  });
};

/**
 * Initialize
 *
 * @see https://webpack.js.org/api/hot-module-replacement
 */
domReady(main);
import.meta.webpackHot?.accept(main);
