<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        factory(\App\Author::class, 10)->create()->each(function($author){
            $author->ratings()->saveMany(
                factory(\App\Rating::class, rand(20,50))->make()
            );
            $booksCount = rand(1,5);

            while ($booksCount>0){
                $book = factory(\App\Book::class)->make();
                $author->books()->save($book);
                $book->ratings()->saveMany(
                    factory(\App\Rating::class, rand(20,50))->make()
                );
                $booksCount--;
            }
        });
        */
        factory(\App\User::class, 10)->create()->each(function ($user) {
            $postCount = rand(1, 5);

            while ($postCount > 0) {
                $post = factory(\App\Post::class)->make();
                $user->posts()->save($post);
                $postCount--;
            }

            $commentsCount = rand(1, 9);

            while ($commentsCount > 0) {
                $comment = factory(\App\Comment::class)->make();
                $comment->post()->associate(\App\Post::inRandomOrder()->first());
                $user->comments()->save($comment);
                $commentsCount--;
            }
        });
    }
}
