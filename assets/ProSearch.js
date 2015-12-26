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
    var tabIndex = -1;
    var shortcut_labels = [];

    function listenToInput()
    {
        var el = document.getElementById("id_searchProInputField");

        $(el).addEvent('keyup', function(e){

            //get q
            var value = $(e.target).get('value');

            if(!value || value.length < 2) return;

            var hasSC = value.split(':');
            if(hasSC.length >= 2)
            {
                if(hasSC[1].length < 2 ) return;
            }

            if(timeOut != null) clearTimeout(timeOut);

            // settings
            var settings = {
                q: value
            };

            timeOut = setTimeout(function(){

                addItems(settings);

            }, 500);
        });

        setArrowDownEvent();
    }

    function setArrowDownEvent()
    {
        var el = $$(document.getElementById("id_searchProInputField"));

        if(el.length > 0)
        {

            $$(document).addEvent('keydown:keys(down)', function(e){

                var results = $$('a.search-result');

                if(results.length < 1) return;

                tabIndex++;

                var count = results.length ? results.length: 0;

                if(tabIndex >= count)
                {
                    tabIndex = 0;
                }

                results[tabIndex].focus();

            });

            $$(document).addEvent('keydown:keys(up)', function(e){

                var results = $$('a.search-result');

                if(results.length < 1) return;

                tabIndex--;

                if(tabIndex < 0)
                {
                   tabIndex = results.length - 1;
                }

                results[tabIndex].focus();

            });

        }
    }

    /**
     * data load from search index
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
        var SearchResultsView = $$('#id_search-results');
        var q = settings.q;

        SearchResultsView.set('html', '<span class="loading"></span>');

        new Request( {'url': url, onSuccess: function(searchData)
        {

            var jsonData = JSON.parse(searchData);
            var response = jsonData['response'];

            shortcut_labels = jsonData['shortcut_labels'];

            //render view
            SearchResultsView.set('html', ItemsView(response));

        }}).get({
                'rt':Contao.request_token,
                'ajaxRequestForProSearch': 'getSearchIndex',
                'searchQuery': q
            });
    };


    function menuView()
    {
        var $template = '' +
            '<div class="menu-overlay" id="id_menu-overlay">' +
                '<div class="menu-align">' +
                    '<div class="menu-wrapper">' +
                        '<div class="menu">'+
                            '<div class="menu-inside">' +
                                '<div class="search-input">' +
                                    '<input type="text" name="searchProInputField" id="id_searchProInputField" placeholder="ProSearch" tabindex="1">'+
                                '</div>'+
                                '<div class="view-panel">' +
                                    '<div class="search-results" id="id_search-results"></div>'+
                                '</div>'+
                                '<div class="search-settings">' +

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
            '<div class="<%= cssClass %> <%= n %>">' +
                '<div class="category-results">'+
                	'<%= buttonsStr %>'+
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

        _.each(data, function(item, category){

            var category_label = shortcut_labels[category];

	        $template += ItemsCategory(item, category_label);
	        
        });

        $template += endWrapper;

        var render = _.template($template);
        return render(options);
        
    }
    
    /**
	 *
	 */   
    function ItemsCategory(item, category)
    {
	    
	    var $template = '';

	    $template += '<div class="result-category"><div class="result-category-header"><%= category %></div>';
	    
	    _.each(item, function(row, i){
							
			row['n'] = i % 2 ? 'even' : 'odd';
            row['cssClass'] = 'result';

            if(i == 0)
            {
                row['cssClass'] = 'result first';
            }

            if(i == item.length - 1)
            {
                row['cssClass'] = 'result last';
            }

            $template += ItemView(row);
		
		});
	    
	    $template += '</div>';	  
	    
	    var render = _.template($template);
	    var obj = {
		    category: category
	    };
        return render(obj);
        
    }

    //
    window.addEvent('domready', function() {
	    
	    
	    var tpl = '<span id="openProSearch" class="header_prosearch_container"><a href="#!prosearch" class="header_prosearch" title="">ProSearch</a></span>'
	    
	    	    
	    /**
	     * event
	     */
        var _userSettings = UserSettings ? UserSettings : {};
        var shortcut = _userSettings.shortcut ? _userSettings.shortcut : 'alt+space';

	    document.addEvent('keydown:keys('+shortcut+')', function(e){
	
	        e.preventDefault();
			
			var body = $$('body');
			body.toggleClass('searchMenuActive');
            var menu;

            // load menu
	        if(body.hasClass('searchMenuActive')[0])
	        {
	            body.appendHTML(menuView());
	            document.getElementById('id_searchProInputField').focus();
	            listenToInput();
	        }
	        
	        // remove search
	        if(!body.hasClass('searchMenuActive')[0])
	        {
                menu = $$("#id_menu-overlay");

                if(menu.length)
                {
                    $$(document).removeEvent('keydown:keys(down)');
                    $$(document).removeEvent('keydown:keys(up)');
                    tabIndex = -1;
                    menu.destroy();
                }
	        }
	        
	        menu = $$("#id_menu-overlay");

	        if(menu.length)
	        {
		    	menu.addEvent('click', function(e){
				
					e.stopPropagation();
					
					var el = $(e.target);
					
							
					if(el.hasClass('menu-wrapper'))
					{
						menu.destroy();
						body.toggleClass('searchMenuActive');
					}
				
				});   
	        }
	
	    });
	    
	    // remove menu by escape key
	    document.addEvent('keydown:keys(esc)', function(e){
	
	        e.preventDefault();
			
			var body = $$('body');
			body.toggleClass('searchMenuActive');
			
	        // remove search
	        if(!body.hasClass('searchMenuActive')[0])
	        {		       
		        var menu = document.getElementById("id_menu-overlay");

                $$(document).removeEvent('keydown:keys(down)');
                $$(document).removeEvent('keydown:keys(up)');
                tabIndex = -1;

                $(menu).destroy();
	        }
	        
	
	    });
	    
	    //add header btn
	    var header = $$('#tmenu');
        header.appendHTML(tpl, 'top');
        
        $$('#openProSearch').addEvent('click', function(e){
	    	e.preventDefault();
	        document.fireEvent('keydown:keys('+shortcut+')', e);    
        });
        
    });

})();