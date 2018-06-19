'use strict';

define([
		'jquery',
        'underscore',
        'pim/form',
        'pim/fetcher-registry',
        'routing',
        'pim/initselect2',
        'pim/user-context',
        'pim/i18n'
	],
    function($, _, BaseForm, fetcherRegistry, Routing, initSelect2, UserContext, i18n) {
    	return BaseForm.extend({
    		configure: function() {
    			this.listenTo(this.getRoot(), 'pim_enrich:form:field:extension:add', this.addFieldExtension);
    		},
            addFieldExtension: function(event) {
                // TODO
            	return this;
            },
    	});
    }
);