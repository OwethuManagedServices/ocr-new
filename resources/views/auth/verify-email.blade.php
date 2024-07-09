<?php
$oData = [
	"buttons" => [
		[
			"post" => "{{route('verification.send')}}",
			"style" => "width:35px;height:25px;float:left;",
			"svg" => "badge-check",
			"fill" => "fill-stone-200",
			"text" => "Resend Link|By Email|15",
		],
	],
	"text" => [
		"Verify Email Address",
		"Congratulations! You are registered. Please use the verification link in your email to progress.",
		"",
		"Login",
		"",
	]
];
?>
<x-home.head title="Verify Email" />

</head>
<body class="bg-light text-dark antialiased w-full max-w-6xl mx-auto">
	<x-home.header />
<div class="w-11/12 sm:w-full max-w-sm mx-auto mt-4">

<div class="w-full max-w-sm mx-auto">
<div class="text-left mb-4">
				<span class="text-3xl drop-shadow-[1px_1px_var(--tw-shadow-color)] shadow-bluemedium">
					<p>{!! __('auth.ve_heading') !!}</p>
				</span>
				<p class="text-lg">
					<span>{!! __('auth.ve_copy') !!}</span>
				</p>
			</div>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

				<x-home.button type="submit" text="{!! __('auth.ve_resendlink') !!}" />

            </form>

            <div class="flex items-center justify-end -mt-6">
                
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="underline text-sm text-dark hover:text-dark rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bluemedium ml-2">
					{!! __('auth.ve_logout') !!}
                    </button>
                </form>
            </div>
        </div>
</body>
</html>
