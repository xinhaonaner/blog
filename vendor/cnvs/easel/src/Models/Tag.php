<?php

namespace Canvas\Models;

use Laravel\Scout\Searchable;
use Canvas\Helpers\CanvasHelper;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Searchable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'canvas_tags';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['reverse_direction' => 'boolean'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag',
        'title',
        'subtitle',
        'meta_description',
        'reverse_direction',
        'created_at',
        'updated_at',
    ];

    /**
     * Searchable items.
     *
     * @var array
     */
    public $searchable = [
        'tag',
        'title',
        'subtitle',
        'meta_description',
    ];

    /**
     * Get the posts relationship.
     *
     * @return BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, CanvasHelper::TABLES['post_tag'])->withTimestamps();
    }

    /**
     * Add tags from the list.
     *
     * @param array $tags List of tags to check/add
     */
    public static function addNeededTags(array $tags)
    {
        if (count($tags) === 0) {
            return;
        }
        $found = static::whereIn('tag', $tags)->pluck('tag')->all();
        foreach (array_diff($tags, $found) as $tag) {
            static::create([
                'tag' => $tag,
                'title' => $tag,
                'subtitle' => 'Subtitle for '.$tag,
                'meta_description' => '',
                'reverse_direction' => false,
            ]);
        }
    }

    /**
     * Return the index layout to use for a tag.
     *
     * @param string $tag
     * @param string $default
     * @return string
     */
    public static function layout($tag, $default = 'frontend.blog.index')
    {
        $layout = static::whereTag($tag)->pluck('layout');

        return $layout ?: $default;
    }
}
