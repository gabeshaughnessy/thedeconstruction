//JS Utility for getting URL parameters

// Get object of URL parameters
// ---   jQuery.getQueryVars();

// Getting URL var by its nam
// ---   jQuery.getQueryVar('name');

jQuery.extend({
  getQueryVars: function(theUrl){
    if(theUrl === undefined || theUrl === ""){
        theUrl = window.location.href;
    }
    var vars = [], hash;
    var hashes = theUrl.slice(theUrl.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
      hash = hashes[i].split('=');
      vars.push(hash[0]);
      vars[hash[0]] = hash[1];
    }
    return vars;
  },
  getQueryVar: function(name, theUrl){
    return jQuery.getQueryVars(theUrl)[name];
  }
});
