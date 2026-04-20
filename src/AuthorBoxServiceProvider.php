<?php

namespace Contensio\Plugins\AuthorBox;

use Contensio\Models\Content;
use Contensio\Models\ContentTranslation;
use Contensio\Plugins\AuthorBox\Support\AuthorProfile;
use Contensio\Support\Hook;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthorBoxServiceProvider extends ServiceProvider
{
    protected string $ns = 'contensio-author-box';

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', $this->ns);

        $this->registerRoutes();

        // Inject author box below post content (priority 5 - renders before share buttons at 10)
        Hook::add('contensio/frontend/post-after-content', function (Content $content, ContentTranslation $translation): string {
            $author = $content->author;

            if (! $author || ! $author->bio) {
                return '';
            }

            $socialLinks = AuthorProfile::socialLinks($author->id);

            return view($this->ns . '::partials.author-box', compact('author', 'socialLinks'))->render();
        }, 5);

        // Add social links form to the profile page
        Hook::add('contensio/admin/profile-sections', function ($user): string {
            $socialLinks = AuthorProfile::socialLinks($user->id);
            return view($this->ns . '::admin.social-links', compact('user', 'socialLinks'))->render();
        });
    }

    private function registerRoutes(): void
    {
        if (! $this->app->routesAreCached()) {
            Route::middleware(['web', 'auth', 'contensio.admin'])
                ->prefix('account/profile')
                ->group(__DIR__ . '/../routes/web.php');
        }
    }
}
