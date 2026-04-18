<?php

namespace Contensio\Plugins\AuthorBox\Support;

use Contensio\Models\UserMeta;
use Illuminate\Database\Eloquent\Collection;

class AuthorProfile
{
    public const META_KEYS = ['x_url', 'facebook_url', 'linkedin_url', 'instagram_url', 'website_url'];

    /**
     * Return all social link meta values for a user as an associative array.
     * Keys are the short names (x_url, facebook_url, …).
     */
    public static function socialLinks(int $userId): array
    {
        $rows = UserMeta::where('user_id', $userId)
            ->whereIn('meta_key', self::prefixedKeys())
            ->whereNull('language_id')
            ->pluck('meta_value', 'meta_key');

        $links = [];
        foreach (self::META_KEYS as $key) {
            $links[$key] = $rows[self::prefix($key)] ?? '';
        }

        return $links;
    }

    /**
     * Persist social links for a user. Upserts each key individually so
     * existing unrelated meta is untouched.
     */
    public static function saveSocialLinks(int $userId, array $data): void
    {
        foreach (self::META_KEYS as $key) {
            $value = trim($data[$key] ?? '');

            UserMeta::updateOrCreate(
                [
                    'user_id'     => $userId,
                    'meta_key'    => self::prefix($key),
                    'language_id' => null,
                ],
                ['meta_value' => $value ?: null]
            );
        }
    }

    private static function prefix(string $key): string
    {
        return 'author_box_' . $key;
    }

    private static function prefixedKeys(): array
    {
        return array_map(fn ($k) => self::prefix($k), self::META_KEYS);
    }
}
