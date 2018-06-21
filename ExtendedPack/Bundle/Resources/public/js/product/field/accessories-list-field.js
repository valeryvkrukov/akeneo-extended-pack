'use strict';

define([
		'jquery',
		'pim/field',
		'underscore',
		'oro/translator',
		'routing',
		'pim/attribute-manager',
		'extended-pack/templates/product/field/accessories-list',
		'extended-pack/templates/product/field/accessories-list-item',
		'pim/dialog',
		'oro/mediator',
		'oro/messenger',
		'pim/media-url-generator',
		'pim/fetcher-registry',
		'jquery.slimbox',
		'pim/user-context',
		'jquery.select2'
	],
	function(
		$, 
		Field, 
		_, 
		__,
		Routing, 
		AttributeManager, 
		fieldTemplate, 
		itemTemplate, 
		Dialog, 
		mediator, 
		messenger, 
		MediaUrlGenerator,
		FetcherRegistry,
		UserContext
	) {
		return Field.extend({
			fieldTemplate: _.template(fieldTemplate),
			listItemTemplate: _.template(itemTemplate),
			selectedItem: null,
			select2: null,
			events: {
				'click .item-add': 'addItem',
				'click .item-remove': 'removeItem',
				'change input[type="text"]': 'updateValue',
				'change .select2': 'createOption'
			},
			configure: function() {
				return $.when(
	                BaseField.prototype.configure.apply(this, arguments),
	            );
			},
			renderInput: function(context) {
				let $element = $(this.fieldTemplate(context));
				this.select2 = $element.find('.select2');
				this.renderListItems($element.find('ul'));
				this.renderSelect2();
				return $element;
			},
			renderSelect2: function() {
				let choiceUrl = this.getChoiceUrl();
				this.select2.select2({
					ajax: {
						url: choiceUrl,
						cache: true,
						data: function(term, page) {
                            return {
                                search: term,
                                options: {
                                    limit: 20,
                                    page: page,
                                    catalogLocale: ''
                                }
                            };
                        },
                        results: function(response) {
                        	if (response.results) {
                                response.more = 20 === _.keys(response.results).length;
                                return response;
                            }
                            var data = {
                                more: 20 === _.keys(response.results).length,
                                results: []
                            };
                            _.each(response, function(value) {
                            	data.push(value);
                            }.bind(this));
                            return data;
                        }
                    },
                    placeholder: ' ',
                    allowClear: true
				});
			},
			formatChoices: function(refData) {
				return _.mapObject(refData, function(refDataItem) {
					return refDataItem.name;
				});
			},
			renderListItems: function(ul) {
				ul.children('li').remove();
				var data = this.getCurrentValue().data;
				_.each(data, function(item, index) {
					ul.append(this.listItemTemplate({
						value: item,
						mediaUrlGenerator: MediaUrlGenerator,
						editMode: this.getEditMode()
					}));
				}, this);
				ul.children('li').each(function(i) {
					$(this).attr('position', i);
				});
			},
			postRender: function() {
				this.$('ul').sortable({
					axis: 'y',
					cursor: 'move',
					handle: '.icon-reorder',
					update: this.sortOrder.bind(this),
					start: function(e, ui) {
						ui.placeholder.height(ui.helper.outerHeight());
					},
					tolerance: 'pointer',
					helper: function(e, item) {
						var originals = item.children();
						var helper = item.clone();
						helper.children().each(function(index) {
							$(this).width(originals.eq(index).outerWidth());
						});
						return helper;
					},
					forcePlaceholderSize: true
				});
			},
			sortOrder: function(e, ui) {
				var sorted = [];
				var positions = this.$('ul').sortable('toArray', {attribute: 'position'});
				for (var i = 0; i < positions.length; i++) {
					sorted.push(this.getCurrentValue().data[positions[i]]);
				}
				this.setCurrentValue(sorted);
				this.$('ul > li').each(function(i) {
					$(this).attr('position', i);
				});
			},
			updateValue: function(e) {
				var index = this.getListItemIndex(e);
				var data = this.getCurrentValue().data;
				var qty = parseFloat(this.$(e.currentTarget).closest('li').find('input[type="text"]').val());
				data[index].qty = qty;
				this.setCurrentValue(data);
			},
			removeItem: function(e) {
				var index = this.getListItemIndex(e);
				var data = this.getCurrentValue().data;
				data.splice(index, 1);
				this.setCurrentValue(data);
				this.renderListItems(this.$('ul'));
			},
			addItem: function() {
                var data = this.getCurrentValue().data;
                data.push(this.selectedItem);
                this.setCurrentValue(data);
                this.renderListItems(this.$('ul'));
                this.$('ul > li:last-child input[type="text"]').focus();
                this.select2.val(null).trigger('change');
			},
			createOption: function(event) {
				if (event.added) {
					let productUrl = this.getProductUrl(event.added.id);
					let that = this;
					$.ajax({
						url: productUrl,
						method: 'POST',
						dataType: 'json',
						success:function(resp) {
							if (resp.product) {
								var data = that.getCurrentValue().data;
								var item = {
									productId: resp.product.id,
									imageUrl: resp.product.imageUrl,
									title: resp.product.title,
									description: resp.product.description,
									qty: 1
				                };
				                that.selectedItem = item;
				            }
						}
					});
				}
			},
			getListItemIndex: function(event) {
				return this.$(event.currentTarget).closest('li').attr('position');
			},
			getSelectFieldValue: function(field) {
				return $(field).val();
			},
            getChoiceUrl: function() {
                return Routing.generate('extended_pack_rest_search');
            },
            getProductUrl: function(id) {
            	return Routing.generate('extended_pack_rest_load', {id: id});
            }
		});
	}
);