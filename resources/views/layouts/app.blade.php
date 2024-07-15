<?php
$oU = Auth::user();
$oTheme = [
    'theme_logo' => $oU->theme_logo,
    'theme_color_primary' => $oU->theme_color_primary,
    'theme_color_secondary' => $oU->theme_color_secondary,
    'theme_color_header' => $oU->theme_color_header,
];
?>
<x-app.head />


				</head>
	<body class="overflow-y-scroll bg-light text-dark relative antialiased" onload="APP.init('{!! base64_encode(json_encode($oTheme)) !!}');">
		<x-banner />

		<div class="min-h-screen mx-auto max-w-7xl">
			<x-app.navigation-menu />

			@if (isset($header))
				<header>
					<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
						{{ $header }}
					</div>
				</header>
			@endif

		<!-- MAIN-CONTENT -->
<main>
				{{ $slot }}
</main>
		<!-- MAIN-CONTENT END-->
		</div>

		@stack('modals')

	</body>
</html>
