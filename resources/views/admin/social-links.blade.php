<form method="POST" action="{{ route('author-box.social-links.update') }}">
    @csrf @method('PUT')
    <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">

        <div>
            <h2 class="text-base font-bold text-gray-900">Social links</h2>
            <p class="text-sm text-gray-500 mt-0.5">Shown on your author box below every post you write.</p>
        </div>

        @php
        $fields = [
            'x_url'         => ['label' => 'X (Twitter)',  'placeholder' => 'https://x.com/yourhandle'],
            'facebook_url'  => ['label' => 'Facebook',     'placeholder' => 'https://facebook.com/yourpage'],
            'linkedin_url'  => ['label' => 'LinkedIn',     'placeholder' => 'https://linkedin.com/in/yourprofile'],
            'instagram_url' => ['label' => 'Instagram',    'placeholder' => 'https://instagram.com/yourhandle'],
            'website_url'   => ['label' => 'Website',      'placeholder' => 'https://yourwebsite.com'],
        ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($fields as $key => $field)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $field['label'] }}</label>
                <input type="url"
                       name="{{ $key }}"
                       value="{{ old($key, $socialLinks[$key] ?? '') }}"
                       placeholder="{{ $field['placeholder'] }}"
                       class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ember-500 focus:border-transparent">
                @error($key)
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endforeach
        </div>

        <div class="flex justify-end pt-1">
            <button type="submit"
                    class="bg-ember-500 hover:bg-ember-600 text-white font-semibold text-sm px-5 py-2 rounded-lg transition-colors">
                Save social links
            </button>
        </div>

    </div>
</form>
