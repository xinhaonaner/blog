<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Article",
        "accessMode": ["textual", "visual"],
        "accountablePerson": {
            "@type": "Person",
            "name": "{{ $user->first_name .  ' ' . $user->last_name }}",
            "email": "{{ $user->email }}"
        },
        "alternativeHeadline": "{!! $post->subtitle !!}",
        @if ($post->page_image)
            "associatedMedia": {
                "@context": "http://schema.org",
                "@type": "ImageObject",
                "contentUrl": "{{ asset($post->page_image) }}",
                "description": "{{ $post->meta_description }}"
            },
            "image": "{{ asset($post->page_image) }}",
            "thumbnailUrl": "{{ asset($post->page_image) }}",
        @endif
        "articleBody": "{{ json_encode($post->content_html, JSON_HEX_QUOT) }}",
        "author": {
            "@type": "Person",
            "name": "{{ $user->first_name .  ' ' . $user->last_name }}",
            "email": "{{ $user->email }}"
        },
        "copyrightHolder": {
            "@type": "Person",
            "name": "{{ $user->first_name .  ' ' . $user->last_name }}"
        },
        "dateCreated": "{{ \Carbon\Carbon::parse($post->created_at)->toIso8601String() }}",
        "dateModified": "{{ \Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}",
        "datePublished": "{{ \Carbon\Carbon::parse($post->published_at)->toIso8601String() }}",
        "description": "{{ $post->meta_description }}",
        "headline": "{{ $post->title }}",
        @php
            $keywordList = [];
            foreach ($post->tags as $tag) {
                $keywordList[] = $tag->tag;
            }
        @endphp
        "keywords": "{{ implode(",",$keywordList) }}",
        "name": "{{ $post->title }}",
        "publisher": {
            "@context" : "http://schema.org",
            "@type" : "Organization",
            "name" : "{{ \Canvas\Models\Settings::blogTitle() }}",
            "url" : "{{ URL::to('/') }}",
            "logo": {
                "@context": "http://schema.org",
                "@type": "ImageObject",
                "contentUrl": "{{ asset($post->page_image) }}",
                "description": "{{ \Canvas\Models\Settings::blogSubtitle() }}",
                "url": "{{ asset($post->page_image) }}"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ Request::url() }}"
        },
        "text": "{{ json_encode($post->content_html, JSON_HEX_QUOT) }}",
        "timeRequired": "{{ $post->readingTime() . 'M0S' }}",
        "wordCount": "{{ str_word_count($post->content_raw) }}"
    }
</script>
