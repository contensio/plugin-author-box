<?php

namespace Contensio\Plugins\AuthorBox\Http\Controllers;

use Contensio\Plugins\AuthorBox\Support\AuthorProfile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SocialLinksController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'x_url'         => ['nullable', 'url', 'max:255'],
            'facebook_url'  => ['nullable', 'url', 'max:255'],
            'linkedin_url'  => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'website_url'   => ['nullable', 'url', 'max:255'],
        ]);

        AuthorProfile::saveSocialLinks($request->user()->id, $validated);

        return back()->with('success', 'Social links updated.');
    }
}
