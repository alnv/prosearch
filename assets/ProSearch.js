/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   Pro Search
 * @author    Alexander Naumov http://www.alexandernaumov.de
 * @license   commercial
 * @copyright 2015 Alexander Naumov
 */

(function(){

    'use strict';

    // SEARCH MODEL
    var SearchItem = Backbone.Model.extend({

    });

    // SEARCH COLLECTION
    var SearchItems = Backbone.Collection.extend({
        model: SearchItem
    });

    // VIEW

    // wrapper
    var SearchWrapper = Backbone.View.extend({

        // construct
        initialize: function(){

        },

        // render template
        render: function(){
            /*
            var self = this;
            this.collection.each(function (model) {

                self.addSearchItem(model);

            });
            */
            return this;
        },

        addSearchItem: function(data){

            var searchItemView = new SearchItemView({model: data});

        }

    });

    // item
    var SearchItemView = Backbone.View.extend({

        // construct
        initialize: function()
        {

        },

        // html
        template: _.template('<p>Item</p>'),

        // render template
        render: function(){

            this.$el.html(this.template(this.model.attributes));
            return this;
        }

    });


    // init search
    var collection = new SearchItem();
    var view = new SearchWrapper({collection: collection});
    var template = view.render().$el;
    console.log(template);

})();