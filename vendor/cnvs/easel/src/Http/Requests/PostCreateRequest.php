<?php

namespace Canvas\Http\Requests;

use Canvas\Helpers\CanvasHelper;
use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'slug' => 'required|unique:'.CanvasHelper::TABLES['posts'],
            'subtitle' => 'required',
            'published_at' => 'required',
        ];
    }

    /**
     * Return the fields and values to create a new post from.
     */
    public function postFillData()
    {
        return [
            'user_id' => $this->user_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'subtitle' => $this->subtitle,
            'page_image' => $this->page_image,
            'content_raw' => $this->get('content'),
            'meta_description' => $this->meta_description,
            'is_published' => (bool) $this->is_published,
            'published_at' => $this->published_at,
            'layout' => config('blog.post_layout'),
        ];
    }
}
