<?php

namespace App\Transformers;

use League\Fractal;
use App\Post;

class PostTransformer extends Fractal\TransformerAbstract
{
    public function transform(Post $post)
    {
        return $post->toArray();
    }
}
