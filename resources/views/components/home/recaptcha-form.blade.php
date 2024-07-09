<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHAV3_SITEKEY') }}"></script>
<script>
	console.log(123)
APP.recaptchasubmit = function(){
	var eB;
	grecaptcha.ready(function() {
		grecaptcha.execute("{{ env('RECAPTCHAV3_SITEKEY') }}", {action:"validate_captcha"})
		.then(function(token) {
			document.getElementById("g_recaptcha_response").value = token;
			eB = document.getElementById("btnsubmit");
			eB.type = "submit";
			eB.onclick = null;
			eB.click();
		});
	});

};

</script>
