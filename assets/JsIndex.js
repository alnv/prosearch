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

        // loop through all modules
        for(var i = 0; i < activeModules.length; i++)
        {

            // send request for indexing
            new Request.Contao( {'url':window.location.href, 'followRedirects':false, onSuccess: function(li)
            {
                $$('.index_list ul').appendHTML(li);

            }}).get({'index':activeModules[i], 'rt':Contao.request_token});

        }

        return false;

    };

})();