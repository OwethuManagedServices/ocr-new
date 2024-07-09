<?php
$oData = [
	"routes" => [
		
			'login',

		
	],
	"text" => [
		"Log in Account",
		"Enter your credentials",
		"Remember me",
		"Forgot Password",
		"Login"
	]
];

//echo json_encode($oData, JSON_PRETTY_PRINT);die;
?>
<x-home.head title="Login" />

</head>
<body class="bg-light text-dark antialiased w-full max-w-6xl mx-auto">
	<x-home.header />
<div class="w-11/12 sm:w-full max-w-sm mx-auto mt-4">
<div class="w-full mx-auto">
</div>
<div class="clear-both"></div>
<div class="text-left mb-4">
				<span class="text-2xl font-semibold">
					<p>{!! __('auth.li_heading') !!}</p>
				</span>
				<p class="text-base">
					<span>{!! __('auth.li_copy') !!}</span>
				</p>
			</div>

				<x-validation-errors class="mb-4" />

@if (session('status'))
	<div class="mb-4 font-medium text-sm text-accgreen">
		{{ session('status') }}
	</div>
@endif

<form method="POST" action="{{ route('login') }}">
	@csrf

	<div>
		<x-label for="email" value="{!! __('auth.li_email') !!}" />
		<x-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
	</div>

	<div class="mt-4">
		<x-label for="password" value="{!! __('auth.li_password') !!}" />
		<x-input id="password" class="block mt-1 w-full bg-white text-black" type="password" name="password" required />
	</div>

	<div class="block mt-4">
		<label for="remember_me" class="flex items-center">
			<x-checkbox id="remember_me" name="remember_me" />
			<span class="ml-2 text-sm  bg-white text-black">{!! __('auth.li_rememberme') !!}</span>
		</label>
	</div>

	<div class="flex items-center justify-end mt-4 mb-16">
		@if (Route::has('password.request'))
			<a class="underline text-sm text-blue hover:text-black  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bluem" href="{{ route('password.request') }}">{!! __('auth.li_forgotpassword') !!}
			</a>&nbsp;&nbsp;&nbsp;
		@endif
		<x-home.button text="{!! __('auth.li_button') !!}" />
	
	</div>
</form>
</div>
</body>
</html>
