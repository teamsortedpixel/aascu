// from here: https://stackoverflow.com/questions/8961770/similar-to-jquery-closest-but-traversing-descendants
(function($) {
    $.fn.closestChild = function(filter) {
        var $found = $(),
            $currentSet = this; // Current place
        while ($currentSet.length) {
            $found = $currentSet.filter(filter);
            if ($found.length) break;  // At least one match: break loop
            // Get all children of the current set
            $currentSet = $currentSet.children();
        }
        return $found.first(); // Return first match of the collection
    }    
})(jQuery);