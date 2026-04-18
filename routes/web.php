<?php

use Contensio\Plugins\AuthorBox\Http\Controllers\SocialLinksController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'contensio.admin'])
    ->prefix('account/profile')
    ->group(function () {
        Route::put('social-links', [SocialLinksController::class, 'update'])
            ->name('author-box.social-links.update');
    });
