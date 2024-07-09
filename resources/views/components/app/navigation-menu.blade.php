<?php

$aMenus = [

	['Admin', 
		['Access Permissions', 'admin-permission.index', 'admin-access-control'],
		['Access Roles', 'admin-role.index', 'admin-access-control'],
		['Users', 'admin-users.index', 'admin-users'],
	],

	['Help',
		['Index', 'help-index', ''],
		['About', 'help-about', ''],
		['Access Control Lists', 'help-acls', ''],
		['Show Welcome Screen Again', 'help-welcome-screen-on', ''],
	],


];
$sM = '';
$oU = Auth::user();
$bTheme = 0;
$sColorHeader = '';
$sLogo = '';
//if (isset($oU->client_id)){
	$sColorPrimary = $oU->theme_color_primary;
	$sColorSecondary = $oU->theme_color_secondary;
	$sColorHeader = $oU->theme_color_header;
	$sLogo = $oU->theme_logo;
	if (($sColorPrimary) && ($sColorSecondary) && ($sColorHeader) && ($sLogo)){
		$bTheme = 1;
	}
	if ($bTheme){
		$sColorHeader = 'style="background:' . $sColorHeader . ';"';
		$sLogo = '<img src="' . $sLogo . '" />';
	}
//}
foreach ($aMenus as $aMenu){
	$sM1 = '
<div class="float-left dropdown" tabindex="1">
	<i class="db2" tabindex="1"></i>
	<a style="--hoverbg:' . $sColorSecondary . '" class="hovering pt-1 dropbtn text-greylight font-medium text-sm">
	' . $aMenu[0] .
	'</a>
	<div class="dropdown-content w-48 bg-greymid text-dark border-bluemedium">
';
	$sM2 = '';
	for ($iI = 1; $iI < sizeof($aMenu); $iI++){
		$sR = route($aMenu[$iI][1]);
		$sRole = $aMenu[$iI][2];
		if ((!$sRole) || (Auth::user()->hasRole($sRole))){
			$sM2 .= '		<a style="--hoverbg:' . $sColorSecondary . '" class="hovering" onclick=\'window.open("' . $sR . '", "_parent");\'>';
			$sM2 .= $aMenu[$iI][0] . '</a>
';
		}
	}
	if ($sM2){
		$sM .= $sM1 . $sM2 . '	</div>
</div>';
	}
}

?>
<!-- NAVIGATION-MENU -->
<div class="fixed bg-blue w-full max-w-7xl" <?= $sColorHeader ?>>

		<div class="justify-between h-8">
			<div class="float-left ml-4">
				<!-- Logo -->
				<a href="{{ route('dashboard') }}">
					<div class="mt-[1px] w-8 h-8 shrink-0 flex items-center">
@if (!$bTheme)
						<x-icons.logo-acc class="block h-4 w-auto" />
@else
	{!! $sLogo !!}
@endif
					</div>
				</a>
				<x-icons.waitspin-shield :size=32 color="#3b82f6" />
			</div>
{!! $sM !!}


<div class="dropdown float-right" style="margin:-1px 16px 0 0 !important;" tabindex="1">
	<i class="db3" tabindex="1"></i>
	<a class="dropbtn" style="padding:0 !important;">

	@if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
<div class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-greylight transition mt-[1px]">
	<img class="h-7 w-7 object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
</div>
@else
<span class="inline-flex rounded-md">
	<button type="button" class="inline-flex items-center px-3 border border-transparent text-sm leading-4 font-medium text-light hover:bg-hoverlight focus:outline-none transition ease-in-out duration-150">
		{{ Auth::user()->name }}

		<svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
			<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
		</svg>
	</button>
</span>
@endif


	</a>
	 <div class="-ml-40 w-48 dropdown-content bg-greymid text-dark rounded border-bluemedium">



			<a style="--hoverbg:{{ $sColorSecondary }}" class="hovering" onclick="APP.themeswitch(1);">
<?php
if (Auth::user()->html_theme) $sCh = 'checked="checked"'; else $sCh = '';
?>

								<div class="text-dark">
        							<label for="darkmode">Dark Mode&nbsp;&nbsp;
                						<input disabled="disabled" class="text-right" type="checkbox" id="darkmode"
                        {{$sCh}} >
        							</label>
									<div class="absolute top-0 left-0 h-10 right-0"></div>

								</div>
							

			
			</a>
			@if (Auth::user()->hasRole('user-manage'))
			<div class="border-t border-bluemedium"></div>
			<div class="block px-4 pt-2 text-xs">
				{{ __('Manage Account') }}
			</div>
			<a style="--hoverbg:{{ $sColorSecondary }}" class="hovering" onclick="window.open('{{ route('profile.show') }}', '_parent');">
				{{ __('Profile') }}</a>
			<a style="--hoverbg:{{ $sColorSecondary }}" class="hovering" onclick="window.open('{{ route('profile.show') }}', '_parent');">
				{{ __('Settings') }}</a>
			@endif
			@if ((Laravel\Jetstream\Jetstream::hasTeamFeatures()) && (Auth::user()->hasRole('user-teams')))
			<div class="border-t border-bluemedium"></div>
			<!-- Team Settings -->
			<div class="block px-4 pt-2 text-xs">
				{{ Auth::user()->currentTeam->name }}
			</div>
			
			<a style="--hoverbg:{{ $sColorSecondary }}" class="hovering" onclick="window.open('{{ route('teams.show', Auth::user()->currentTeam->id) }}', '_parent');">
				{{ __('Team Settings') }}
			</a>

			@can('create', Laravel\Jetstream\Jetstream::newTeamModel())
						<a style="--hoverbg:{{ $sColorSecondary }}" class="hovering" onclick="window.open('{{ route('teams.create') }}', '_parent');">
							{{ __('Create New Team') }}
						</a>
					@endcan

					<!-- Team Switcher -->
					@if (Auth::user()->allTeams()->count() > 1)
						<div class="border-t border-greydark"></div>

						<div class="block px-4 py-2 text-xs text-dark">
							{{ __('Switch Teams') }}
						</div>

						@foreach (Auth::user()->allTeams() as $team)
							<x-switchable-team :team="$team" />
						@endforeach
					@endif


					@if ((Laravel\Jetstream\Jetstream::hasApiFeatures()) && (Auth::user()->hasRole('user-api-tokens')))
					<div class="border-t border-bluemedium"></div>
				<a style="--hoverbg:{{ $sColorSecondary }}" class="hovering" onclick="window.open('{{ route('api-tokens.index') }}', '_parent');">
					{{ __('API Tokens') }}
				</a>
			@endif
@endif
<div class="border-t border-bluemedium"></div>
<form method="POST" action="{{ route('logout') }}" x-data>
								@csrf

								<a style="--hoverbg:{{ $sColorSecondary }}" class="hovering" onclick="APP.logout();"
										 @click.prevent="$root.submit();">
									{{ __('Log Out') }}
								</a>
								<button class="hidden" type="submit" id="logoutsubmit">Log out</button>
							</form>
			
	 </div>
</div>


			</div>
			<div id="main-header"></div>
			<div id="main-header2"></div>

		</div>
		<div id="main-header-height" class="w-full bg-red clear-both"></div>
		

		<!-- NAVIGATION-MENU END -->
