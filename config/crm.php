<?php
// config/crm.php
return [
'middleware' => [
'redirect.if.from.google' => \Taba\Crm\Http\Middleware\RedirectIfFromGoogle::class,
'remove.public.from.url' => \Taba\Crm\Http\Middleware\RemovePublicFromUrl::class,
'google.translate' => \Taba\Crm\Http\Middleware\GoogleTranslate::class,
'force.https' => \Taba\Crm\Http\Middleware\ForceHttps::class,
],
];