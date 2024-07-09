<x-home.head title="Home"/>

	</head>
	<body class="bg-light text-dark w-full max-w-5xl mx-auto">
	<x-home.header />
	<main class="text-center mx-auto bg-yellow w-full max-w-4xl rounded-md">
		<div class="w-full grid grid-cols-1 md:grid-cols-[60%_35%] gap-8 mb-8 mt-32 mb-32 px-4 py-8">
			<div>
				<img class="w-full h-full rounded-md" src="/img/background.png" />
			</div>
			<div class="bg-red rounded-md mx-auto text-left ">
				<div class="p-4">This proof-of-concept presentation will demonstrate the performance and capabilities of our OCR bank statement analysis system.<br><br>Please log in to continue.<br><br>					
				</div>
			</div>
			<div class="col-span-2">
				<div class="grid grid-cols-[45%_20%_20%_15%] text-right">
					<div></div>
					<a href="{{ route('login') }}"><x-button>Presentation</x-button></a>
					<a href="{{ route('login') }}"><x-button>Intro Video</x-button></a>
					<a href="{{ route('login') }}"><x-button>Log in</x-button></a>
				</div>
			</div>
		
		</div>
	</main>
</div>
		<x-home.footer />
	</body>
</html>