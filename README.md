# Author Box

Displays a card with the post author's avatar, bio, and social links below every post.

```
┌─────────────────────────────────────────────────┐
│  [Avatar]  Written by                           │
│            Iosif Chimilevschi                   │
│            Full-stack developer and founder … │
│            X  Facebook  LinkedIn  Website       │
└─────────────────────────────────────────────────┘
```

The box only appears when the author has a bio set on their profile. Social links are optional - only configured networks are shown.

---

## Requirements

- Contensio 2.0 or later

---

## Installation

### Composer

```bash
composer require contensio/plugin-author-box
```

### Manual

Copy the plugin directory and register the service provider via the admin plugin manager.

No migrations required - social links are stored in the core `user_meta` table.

---

## How it works

### Frontend

Hooks into `contensio/frontend/post-after-content` at priority **5** (renders before share buttons at 10):

```php
Hook::add('contensio/frontend/post-after-content', function (Content $content, ContentTranslation $translation): string {
    $author = $content->author;
    if (! $author || ! $author->bio) return '';

    $socialLinks = AuthorProfile::socialLinks($author->id);
    return view('author-box::partials.author-box', compact('author', 'socialLinks'))->render();
}, 5);
```

The box is hidden if the post has no author or the author has no bio.

### Admin - social links form

Hooks into `contensio/admin/profile-sections` to append a "Social links" form card to every user's profile page (`/account/profile`).

Social links are saved to `user_meta` with keys prefixed `author_box_` (e.g. `author_box_x_url`).

---

## Avatar

The plugin uses the author's `avatar_path` (uploaded via Admin > My Profile). If no avatar is uploaded, a coloured square with the author's initial is shown - matching the admin panel style.

---

## Supported networks

| Field | Example |
|-------|---------|
| X (Twitter) | `https://x.com/handle` |
| Facebook | `https://facebook.com/page` |
| LinkedIn | `https://linkedin.com/in/profile` |
| Instagram | `https://instagram.com/handle` |
| Website | `https://yoursite.com` |

Fields are URL-validated on save. Empty fields are not displayed.

---

## Customising the layout

Override the Blade view in your theme:

```
resources/views/vendor/author-box/partials/author-box.blade.php
```

Available variables: `$author` (User model), `$socialLinks` (array with keys `x_url`, `facebook_url`, `linkedin_url`, `instagram_url`, `website_url`).

---

## Hook reference

| Hook | Type | Args | Description |
|------|------|------|-------------|
| `contensio/frontend/post-after-content` | Render (priority 5) | `Content, ContentTranslation` | Injects author box below post content |
| `contensio/admin/profile-sections` | Render | `User` | Appends social links form to the profile page |
