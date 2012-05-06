/* Copyright (c) 2006-2011 by OpenLayers Contributors (see authors.txt for 
 * full list of contributors). Published under the Clear BSD license.  
 * See http://svn.openlayers.org/trunk/openlayers/license.txt for the
 * full text of the license. */

/* 
 * @requires OpenLayers/BaseTypes.js
 * @requires OpenLayers/Lang/en.js
 * @requires OpenLayers/Console.js
 */
 
/*
 * TODO: In 3.0, we will stop supporting build profiles that include
 * OpenLayers.js. This means we will not need the singleFile and scriptFile
 * variables, because we don't have to handle the singleFile case any more.
 */

(function() {
    /**
     * Before creating the OpenLayers namespace, check to see if
     * OpenLayers.singleFile is true.  This occurs if the
     * OpenLayers/SingleFile.js script is included before this one - as is the
     * case with old single file build profiles that included both
     * OpenLayers.js and OpenLayers/SingleFile.js.
     */
    var singleFile = (typeof OpenLayers == "object" && OpenLayers.singleFile);
    
    /**
     * Relative path of this script.
     */
    var scriptName = (!singleFile) ? "lib/OpenLayers.js" : "OpenLayers.js";

    /*
     * If window.OpenLayers isn't set when this script (OpenLayers.js) is
     * evaluated (and if singleFile is false) then this script will load
     * *all* OpenLayers scripts. If window.OpenLayers is set to an array
     * then this script will attempt to load scripts for each string of
     * the array, using the string as the src of the script.
     *
     * Example:
     * (code)
     *     <script type="text/javascript">
     *         window.OpenLayers = [
     *             "OpenLayers/Util.js",
     *             "OpenLayers/BaseTypes.js"
     *         ];
     *     </script>
     *     <script type="text/javascript" src="../lib/OpenLayers.js"></script>
     * (end)
     * In this example OpenLayers.js will load Util.js and BaseTypes.js only.
     */
    var jsFiles = window.OpenLayers;

    /**
     * Namespace: OpenLayers
     * The OpenLayers object provides a namespace for all things OpenLayers
     */
    window.OpenLayers = {
        /**
         * Method: _getScriptLocation
         * Return the path to this script. This is also implemented in
         * OpenLayers/SingleFile.js
         *
         * Returns:
         * {String} Path to this script
         */
        _getScriptLocation: (function() {
            var r = new RegExp("(^|(.*?\\/))(" + scriptName + ")(\\?|$)"),
                s = document.getElementsByTagName('script'),
                src, m, l = "";
            for(var i=0, len=s.length; i<len; i++) {
                src = s[i].getAttribute('src');
                if(src) {
                    var m = src.match(r);
                    if(m) {
                        l = m[1];
                        break;
                    }
                }
            }
            return (function() { return l; });
        })()
    };

    /**
     * OpenLayers.singleFile is a flag indicating this file is being included
     * in a Single File Library build of the OpenLayers Library.
     * 
     * When we are *not* part of a SFL build we dynamically include the
     * OpenLayers library code.
     * 
     * When we *are* part of a SFL build we do not dynamically include the 
     * OpenLayers library code as it will be appended at the end of this file.
     */
    /*
    if(!singleFile) {
    	var snode = document.createElement('script');  
    	snode.setAttribute('type','text/javascript');  
    	snode.setAttribute('src','/js/OpenLayers.php');  
    	document.getElementsByTagName('head')[0].appendChild(snode); 
    }*/
})();

/**
 * Constant: VERSION_NUMBER
 */
OpenLayers.VERSION_NUMBER="Release 2.11";
