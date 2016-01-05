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

    AjaxRequest.ajaxSearchIndex = function()
    {
        // set active modules form config
        var activeModules = proSearchActiveModules ? proSearchActiveModules : [];

        // disable btn
        $$('.ajaxSearchIndex').setStyle('display', 'none');

        loadHtml(activeModules, function(){

            // loop through all modules
            for(var i = 0; i < activeModules.length; i++)
            {
                // send request for indexing
                sendRequest(activeModules[i], 0);
            }

        });


        return false;

    };


    function loadHtml(modules, callback)
    {
        setTimeout(function(){

            for(var i = 0; i < modules.length; i++)
            {
                var template = '<div class="ps_alert" id="id_'+modules[i]+'"><span class="loading"></span></div>';
                $$('.index_list .ul').appendHTML(template);
            }

            callback();

        }, 0);
    }

    function sendRequest($table, $page)
    {
	    
	    //ie add origin 9
        if (!window.location.origin) {
	        
			window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
			
		}
	    
        var host = window.location.origin;
        var path = window.location.pathname;
        var ajaxCall = '?do=prosearch_settings';
        var url = host+path+ajaxCall;
        var _page = $page;

        new Request({'url': url, onSuccess: function(data)
        {

            var jsonData = JSON.parse(data);

            var template = '<div class="'+jsonData.state+'"><p><span class="attr">Table: </span><span class="value">'+jsonData.table+' [left: '+jsonData.left+']</span></p></div>';

            $$('#id_'+jsonData.table+'').set('html', template);

            if(jsonData.state == 'repeat')
            {
                var num = jsonData.page + 1;
                return sendRequest(jsonData.table, num);
            }


        }}).get({'rt':Contao.request_token, 'index': $table, 'page': _page});

    }

})();