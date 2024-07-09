<?php
use App\Models\Website;
use Illuminate\Support\Facades\App;
$sAl = App::currentLocale();
if (!$sAl){
    $sAl = 'en';
}
$aText = Website
	::where('language', '=', $sAl)
	->where('category', '=', 'auth')
	->where('key', '=', 'text')
	->get();
return json_decode($aText[0]->value, 1);
