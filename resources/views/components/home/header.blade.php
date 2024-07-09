<?php
$aMenus = [
	[__('header.home'), 'nav_home', '/', 1, 0],

];
$sSmall = '<div class="inline md:hidden mt-1">';
$sMenu = '
<ul class="list-none text-sm lg:text-lg mt-[7px]">';
foreach ($aMenus as $aM){
	if ((isset($active)) && ($active == $aM[1])){
		$sClass = 'text-red ';
	} else {
		$sClass = 'text-dark hover:text-red ';
	}
	if ($aM[3]){
		$sS = '';
	} else {
		$sS = ' hidden md:inline ';
		$sSmall .= '<a class="px-2" href="' . $aM[2] . '">' . $aM[0] . '</a>';
	}
	// submenu
	if ($aM[4]){
		$sMenu .= '<li class="px-2 inline"><div class="menudropdown relative inline-block">
		<button id="' . $aM[1] . '" class="' . $sClass .'border-0">' . $aM[0] . '</button>
		<div class="menudropdownbox rounded-md bg-greylight -ml-20 w-80 absolute z-20 border-greydark border-2">';
		foreach ($aM[4] as $aS){
			$sMenu .= '<a class="p-3 hover:bg-blue hover:text-light" href="' . $aS[1] . '">' . $aS[0] . '</a>';
		}
		$sMenu .= '</div></div></li>';
	} else {
		$sMenu .= '<li id="' . $aM[1] . '" class="' . $sClass . $sS . ' cursor-pointer px-4 inline "><a href="' . $aM[2] . '">' . $aM[0] . '</a></li>';
	}
}
$sMenu .= '</ul>';
$sSmall .='</div>';


?>
<!-- HEADER -->
<header class="fixed top-0 w-full max-w-5xl z-50 text-sm mx-auto bg-light">
	<div class="bg-blue h-9 w-full">


		<div class="float-left px-2 mt-2 text-normal text-light">{!! env('APP_NAME') !!}</div>

		<div class="float-right text-light mt-2 mr-6">
			<div class="float-right">

@if (Route::has('login'))
@auth
					<a href="{{ url('/dashboard') }}" class="font-thin text-greyl hover:text-hoverlight hover:bg-hoverlight focus:outline focus:outline-2 focus:rounded-sm focus:text-bluem">{{ __('header.dashboard') }}</a>
@else
					<span>|</span>
					<a href="{{ route('login') }}" class="p-2 font-light text-greyl hover:text-hoverlight hover:bg-hoverlight focus:outline focus:outline-2 focus:rounded-sm focus:text-bluem ">{{ __('header.login') }}</a>
<?php /*		@if (Route::has('register'))
		<span>|</span>
		<a href="{{ route('register') }}" 
		class="mr-6 font-light text-greyl hover:text-hoverlight hover:bg-hoverlight focus:outline focus:outline-2 focus:rounded-sm focus:text-bluem mr-1">{{ __('header.register') }}</a>
@endif */ ?>
@endauth

@endif
			</div>
			<div class="cursor-pointer float-right ml-4" onclick="APP.themeswitch()">
				<div id="theme_sun" style="display:block" class="float-left mr-3 hover:text-hoverlight hover:bg-hoverlight">
					<x-icons.sun />
				</div>
				<div id="theme_moon" style="display:none" class="float-left mr-3 hover:text-hoverlight hover:bg-hoverlight">
					<x-icons.moon />
				</div>
			</div>
		</div>

	</div>


	<div class="bg-light h-34 border-b border-greylight">
		<div class="float-left ml-4">
			<a href="/">
				<div class="w-40 float-left mt-4">
					<x-icons.logo-acc />
				</div>
			</a>
			<div class="float-left mt-14">
			<div class="text-xl font-bold">Owethu Managed Services</div>
			<div>Solution By Collaboration</div>
			</div>
		</div>

		<div class="float-right mt-1 lg:mt-3 mr-2">
{!! $sSmall !!}
{!! $sMenu !!}
	</div>

	</div>

</header>
<div class="clear-both h-[100px] md:h-[128px]"></div>

		<!-- HEADER END -->
