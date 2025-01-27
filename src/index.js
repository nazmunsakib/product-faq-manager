import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import { createElement, render } from '@wordpress/element';
import Header from './components/Header';

const PfaqmDashboard = () => {
    return <div>Placeholder for settings page</div>;
};

domReady( () => {
    const pfaqmRoot = createRoot(
        document.getElementById( 'pfaqm-dashboard' )
    );

    pfaqmRoot.render( <Header /> );
} );