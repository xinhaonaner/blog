<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Canvas\Models\Tag;

$factory->define(Tag::class, function () {
    return [
        'tag'               => 'Getting Started',
        'title'             => 'Getting Started',
        'subtitle'          => 'Getting started with Canvas',
        'meta_description'  => 'Meta content for this tag.',
        'reverse_direction' => false,
        'created_at'        => Carbon\Carbon::now(),
    ];
});
