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

    var timeOut = null;

    /**
     * event
     */
    document.addEvent('keydown:keys(alt+m)', function(e){

        e.preventDefault();

        // load menu
        if(!$$('body').hasClass('searchMenuActive')[0])
        {
            $$('body').set('class', 'searchMenuActive');
            $$('body').appendHTML(menuView());
            document.getElementById('id_searchProInputField').focus();
            listenToInput();
        }

    });

    /**
     *
     */
    function listenToInput()
    {
        var el = document.getElementById("id_searchProInputField");

        $(el).addEvent('keyup', function(e){

            if(timeOut != null) clearTimeout(timeOut);

            //get q
            var value = $(e.target).get('value');

            // settings
            var settings = {
                q: value
            };

            timeOut = setTimeout(function(){

                addItems(settings)

            }, 250);


        });

    }

    /**
     *
     */
    var addItems = function(settings)
    {
        //reset timeout
        timeOut = null;

        // get search data from search index
        var host = window.location.origin;
        var path = window.location.pathname;
        var ajaxCall = '?do=prosearch_settings';
        var url = host+path+ajaxCall;

        var q = settings.q;

        new Request( {'url': url, onSuccess: function(searchData)
        {
            var jsonData = JSON.parse(searchData);

            //render view
            var SearchResultsView = $(document.getElementById('id_search-results'));
            SearchResultsView.set('html', ItemsView(jsonData));

        }}).get({
                'rt':Contao.request_token,
                'ajaxRequestForProSearch': 'getSearchIndex',
                'searchQuery': q
            });
    };


    function menuView()
    {
        var $template = '' +
            '<div class="menu-overlay">' +
                '<div class="menu-align">' +
                    '<div class="menu-wrapper">' +
                        '<div class="menu">'+
                            '<div class="menu-inside">' +
                                '<div class="search-input">' +
                                    '<input type="text" name="searchProInputField" id="id_searchProInputField" placeholder="Contao - ProSearch" tabIndex="1">'+
                                '</div>'+
                                '<div class="view-panel">' +
                                    '<div class="search-results" id="id_search-results"></div>'+
                                '</div>'+
                                '<div class="search-ettings">' +

                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>' +
            '</div>';

        var render = _.template($template);

        return render();
    }

    /**
     *
     * @param item
     * @returns {string}
     * @constructor
     */
    function ItemView(item)
    {
        var $template =
            '<div class="result <%= n %>">' +
                '<div class="title">' +
                    '<span class="icon"><%= icon %></span> ' +
                    '<a href="#"><%= title %></a> ' +
                    '<span class="info">[<%= docId %>]</span>' +
                '</div>'+
            '</div>';

        var render = _.template($template);

        return render(item);
    }

    /**
     *
     * @param data
     * @returns {string}
     * @constructor
     */
    function ItemsView(data)
    {

        var options = {
            count: data.length
        };

        var startWrapper = '<div class="results <% if( count < 1 ){ %>no-results<%};%>">';
        var endWrapper = '</div>';
        var $template = startWrapper;

        _.each(data, function(item, i){

            item['n'] = i % 2 ? 'even' : 'odd';
            $template += ItemView(item);

        });

        $template += endWrapper;

        var render = _.template($template);

        return render(options);

    }

    //debug
    window.addEvent('domready', function() {
        //$$('body').set('class', 'searchMenuActive');
        //$$('body').appendHTML(menuView());
    });

})();