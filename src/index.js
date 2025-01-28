import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import App from './App';

domReady( () => {
    const pfaqmRoot = createRoot(
        document.getElementById( 'pfaqm-dashboard' )
    );

    pfaqmRoot.render( <App /> );
} );