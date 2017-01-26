/*
 * Get Social Sharing Buttons JS
*/

function getKeyGetSocial() {

	document.getElementById('activate-account').style.display = 'none';
    document.getElementById('loading-create').style.display = 'block';

    new Ajax.Request(document.getElementById('button-activate-account').href, {
       method: 'Post',
       dataType: 'json',
       parameters: {source: 'magento'},
       contentType: 'application/x-www-form-urlencoded',

       onComplete: function(data) {
           result = JSON.parse(data.responseText);
           
           if (result.errors) {
               document.getElementById('register-form').style.display = 'none';
               document.getElementById('api-key-form').style.display = 'block';
               document.getElementById('result-info-bar').innerHTML = result.errors[0];
               document.getElementById('result-info-bar').style.display = 'block';
           } else {
               document.getElementsByName('gs-api-key')[0].value = result.api_key;
               getGetSocialData(result.api_key);
           }
       }
   });
}

function getVerifyKeyGetSocial() {

	document.getElementById("getkey-submit").style.display = "none";
    document.getElementById("getkey-loading-create").style.display = "block";
    var site_api_key = document.getElementById("gs-api-key").value;

    new Ajax.Request(document.getElementById('check-key-href').innerHTML, {
       method: 'Post',
       dataType: 'json',
       parameters: {source: 'magento', api_key: site_api_key},
       contentType: 'application/x-www-form-urlencoded',

       onComplete: function(data) {
           result = JSON.parse(data.responseText);
           
           if (result.errors) {
				document.getElementById('result-info-bar').innerHTML = result.errors;
               	document.getElementById("getkey-loading-create").style.display = "none";
               	document.getElementById("getkey-submit").style.display = "block";
           } else {
               document.getElementsByName('gs-api-key')[0].value = result.api_key;
               getGetSocialData(site_api_key);
           }
       }
   });
}

function getGetSocialData(site_api_key) {
    new Ajax.Request(get_social_api_url + 'sites/' + site_api_key, {
       method: 'get',
       dataType: 'json',
       contentType: 'application/x-www-form-urlencoded',

       onComplete: function(data) {
           result = JSON.parse(data.responseText);
           if (result.errors) {
               document.getElementById('result-info-bar').innerHTML = result.errors[0];
               document.getElementById('result-info-bar').style.display = 'block';
           } else {
               	document.getElementsByName('api-result')[0].value = JSON.stringify(result);
               	
               	document.getElementById("loading-create").style.display = "none";
               	document.getElementById("form-info").style.display = "none";

               	document.getElementById("getkey-loading-create").style.display = "none";
               	document.getElementById("api-key-form").style.display = "none";
               	document.getElementById("result-info-bar").style.display = "none";
               	
               	document.getElementsByClassName('success-message')[0].style.display = 'block';
				setTimeout("document.getElementById('submit-register-form').submit();", 3000);
           }
       }
   });
}

var GetClientKey = Class.create({

    initialize: function(msg) {
        this.msg = msg;
    },

    handleClick: function(event) {
        event.stop();
        getKeyGetSocial();
    }
});

var VerifyClientSecret = Class.create({

    initialize: function(msg) {
        this.msg = msg;
    },

    handleClick: function(event) {
        event.stop();
        getVerifyKeyGetSocial();
    }
});

var registerKey = new GetClientKey();

var verifyKey = new VerifyClientSecret();

document.observe('dom:loaded', function() {

	if ($('button-activate-account')) {
        $('button-activate-account').observe('click', registerKey.handleClick.bind(registerKey));
    }
    if ($('button-insert-key')) {
    	$('button-insert-key').observe('click', verifyKey.handleClick.bind(verifyKey));
   	}
});
