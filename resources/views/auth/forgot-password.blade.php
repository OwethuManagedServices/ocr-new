<?php
$oData = [
	"buttons" => [
		[
			"post" => "{{route('password.email')}}",
			"style" => "width:35px;height:25px;float:left;",
			"svg" => "badge-check",
			"fill" => "fill-stone-200",
			"text" => "Email Password|Reset Link|15",
		],
	],
	"text" => [
		"Forgot Password",
		"We'll email a password reset link that will allow you to choose a new one.",
		"Back to Login",
		"Email Link"
	]
];
?>

<x-home.head title="Forgot Password" />

</head>
<body class="bg-light text-dark antialiased w-full max-w-6xl mx-auto">
	<x-home.header />
<div class="w-11/12 sm:w-full max-w-sm mx-auto mt-4 sm:mt-24">
<div class="text-left mb-4">
				<span class="text-2xl font-semibold">
					<p>{!! __('auth.fp_heading') !!}</p>
				</span>
				<p class="text-base">
					<span>{!! __('auth.fp_copy') !!}</span>
				</p>
			</div>
            @if (session('status'))
	<div class="mb-4 font-medium text-sm text-light bg-red p-4">
		{{ session('status') }}
	</div>
@endif
<x-validation-errors class="mb-4" />
            <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{!! __('auth.fp_email') !!}" />
                <x-input id="email" class="block mt-1 w-full bg-white text-black" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-blue hover:text-black  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bluem" href="{{ route('login') }}">
				{!! __('auth.fp_backtologin') !!}
                </a>&nbsp;&nbsp;&nbsp;&nbsp;
				<x-home.button type="submit" text="{!! __('auth.fp_button') !!}" />

            </div>
       </form>
        </div>
</body>
</html>
