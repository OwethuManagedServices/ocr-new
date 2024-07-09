<!DOCTYPE html>
<html class="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta property="og:site_name" content="{{ config('app.name', '') }}">
		<meta property="og:title" content="{{ config('app.name', '') }}" />
		<meta property="og:description" content="{{ config('app.name', '') }}" />
		<meta property="og:image" itemprop="image" content="/img/icon.png">
		<meta property="og:type" content="website" />
		<title>{{ config('app.name', '') }} -- {{ $title }}</title>
		<script>var oTheme = localStorage.getItem("{{ config('app.name', '') }}".replace(/ /g, "")); if (oTheme){ oTheme = JSON.parse(oTheme).theme; if (oTheme){  document.getElementsByTagName("html")[0].className = oTheme; } }</script>
		@vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="/js/acc.js"></script>

