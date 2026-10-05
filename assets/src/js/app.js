import '@fontsource/lato/latin-400.css';
import '@fontsource/lato/latin-700.css';
import '../css/app.css';
import { initializeCatalogFilters } from './modules/catalog-filters.js';
import { initializeMiniCart } from './modules/mini-cart.js';
import { initializeNavigation } from './modules/navigation.js';
import { initializeSectionTransitions } from './modules/section-transitions.js';

document.documentElement.classList.add('js');
initializeNavigation();
initializeMiniCart();
initializeCatalogFilters();
initializeSectionTransitions();
