// http://brandonaaron.net/blog/2007/06/17/jquery-snippets-outerhtml/
jQuery.fn.outerHTML = function() { return $('<div>').append( this.eq(0).clone() ).html(); };
