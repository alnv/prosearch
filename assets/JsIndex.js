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

        //var host = window.location.origin;
        //var path = window.location.pathname;
        //var ajaxCall = '?do=prosearch_settings';
        //var url = host+path+ajaxCall;
        //var page = 1;

        // loop through all modules
        for(var i = 0; i < activeModules.length; i++)
        {

            // send request for indexing
            sendRequest(activeModules[i], 1);
            /*
            new Request.Contao( {'url': url, onSuccess: function(data)
            {
                //$$('.index_list ul').appendHTML(li);
                var jsonData = JSON.parse(data);
                console.log(jsonData);

            }}).get({'index':activeModules[i], 'rt':Contao.request_token});
            */


        }

        return false;

    };


    function sendRequest($table, $page)
    {
        var host = window.location.origin;
        var path = window.location.pathname;
        var ajaxCall = '?do=prosearch_settings';
        var url = host+path+ajaxCall;
        var page = $page;

        new Request({'url': url, onSuccess: function(data)
        {

            var jsonData = JSON.parse(data);

            if(jsonData.state == 'repeat')
            {
                var num = jsonData.page + 1;

                return sendRequest(jsonData.table, num);
            }


        }}).get({'rt':Contao.request_token, 'index': $table, 'page': page});

    }

})();