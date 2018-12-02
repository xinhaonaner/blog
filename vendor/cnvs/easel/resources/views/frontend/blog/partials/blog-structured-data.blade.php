<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "Blog",
        "accessMode": ["textual", "visual"],
        "accountablePerson": {
            "@type": "Person",
            "name": "{{ \Canvas\Models\Settings::blogAuthor() }}"
        },
        "alternativeHeadline": "{{ \Canvas\Models\Settings::blogSubtitle() }}",
        "description": "{{ \Canvas\Models\Settings::blogDescription() }}",
        "headline": "{{ \Canvas\Models\Settings::blogTitle() }}",
        "keywords": "{{ \Canvas\Models\Settings::blogSeo() }}",
        "name": "{{ \Canvas\Models\Settings::blogTitle() }}"
    }
</script>
