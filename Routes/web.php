<?php

Route::prefix('epanel/media')->as('epanel.')->middleware(['auth', 'check.permission:Galeri'])->group(function() 
{
    Route::resources([
        'album' => 'AlbumController',
	    'album.galeri' => 'GaleriController'
    ]);
});