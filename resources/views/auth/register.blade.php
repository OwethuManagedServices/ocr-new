<?php
$oData = [
	"buttons" => [
		[
			"post" => "{{route('register')}}",
			"style" => "width:35px;height:25px;float:left;",
			"svg" => "badge-check",
			"fill" => "fill-stone-200",
			"text" => "Register|Account|15",
		],
	],
	"text" => [
		"Register Account",
		"Please supply these details to create an account.",
		"",
		"Login",
		"Back to Login",
	]
];
?>
<x-home.head title="Register Account" />

</head>
<body class="bg-light text-dark antialiased w-full max-w-6xl mx-auto">
	<x-home.header />
	<div class="w-11/12 sm:w-full max-w-sm mx-auto mt-4 sm:mt-24">
<div class="text-left mb-4">
				<span class="text-2xl font-semibold">
					<p>{!! __('auth.rg_heading') !!}</p>
				</span>
				<p class="text-base">
					<span>{!! __('auth.rg_copy') !!}</span>
				</p>
			</div>
				<x-validation-errors class="mb-4" />

@if (session('status'))
	<div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
		{{ session('status') }}
	</div>
@endif

<form method="POST" action="{{ route('register') }}">
			@csrf

			<div>
				<x-label for="name" value="{!! __('auth.rg_name') !!}" />
				<x-input id="name" class="block mt-1 w-full bg-white text-black" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
			</div>

			<div class="mt-4">
				<x-label for="email" value="{!! __('auth.rg_email') !!}" />
				<x-input id="email" class="block mt-1 w-full bg-white text-black" type="email" name="email" :value="old('email')" required autocomplete="username" />
			</div>

			<div class="mt-4">
				<x-label for="password" value="{!! __('auth.rg_password') !!}" />
				<x-input id="password" class="block mt-1 w-full bg-white text-black " type="password" name="password" required autocomplete="new-password" />
			</div>

			<div class="mt-4">
				<x-label for="password_confirmation" value="{!! __('auth.rg_confirmpassword') !!}" />
				<x-input id="password_confirmation" class="block mt-1 w-full bg-white text-black" type="password" name="password_confirmation" required autocomplete="new-password" />
			</div>

			@if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
				<div class="mt-4">
					<x-label for="terms">
						<div class="flex items-center">
							<x-checkbox name="terms" id="terms" required />

							<div class="ml-2">
								{!! __('auth.rg_iagreetothe') !!}
								<a target="_blank" href="{{ route('documents-terms') }}" class="underline text-sm text-blue hover:text-black  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bluem">
								{!! __('auth.rg_terms') !!}</a>
								{!! __('auth.rg_and') !!}
								<a target="_blank" href="{{ route('documents-policy') }}" class="underline text-sm text-blue hover:text-black  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bluem">
								{!! __('auth.rg_policy') !!}</a>
							</div>
						</div>
					</x-label>
				</div>
			@endif

			<div class="flex items-center justify-end mt-4">
				<a class="underline text-sm text-blue hover:text-black  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bluem" href="{{ route('login') }}">
				{!! __('auth.rg_backtologin') !!}
				</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<x-home.button type="submit" text="{!! __('auth.rg_button') !!}" />

			</div>
		</form>
</div>
</body>
</html>
