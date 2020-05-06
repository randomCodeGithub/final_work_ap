<?php

use App\Message;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {

    $posts = \App\Post::orderBy('created_at', 'DESC')->take(3)->get();
    $popularPosts = \App\Post::orderBy('views', 'DESC')->limit(5)->get();
    $postsTops = \App\Post::orderBy('created_at', 'DESC')->where('category', 'Tops')->limit(6)->get();
    $postsGames = \App\Post::orderBy('created_at', 'DESC')->where('category', 'Games')->limit(6)->get();
   
    return view('pages.home',[
        'somePosts' => $posts,
        'popupalPosts' => $popularPosts,
        'postsTops' => $postsTops,
        'postsGames' => $postsGames,
    ]);
});

Route::get('/blog', function () {

    $newestPost = \App\Post::latest()->first();

    
    $posts = \App\Post::orderBy('created_at', 'DESC')->get();

    return view('pages.blog', [
        'newestPost' => $newestPost,
        'otherPosts' => $posts,
    ]);
});

Route::get('/blog/{id}', function ($id) {

    $post = \App\Post::find($id);
    $post->views = $post->views +1;
    $post->save();

    $posts = \App\Post::orderBy('created_at', 'DESC')->limit(3)->get();

if($post == null) {
    return abort(404);
}

return view('pages.blog-item', [
    'currentPost' => $post,
    'posts' => $posts,

]);
});

Route::get('/contact-us', function () {

    return view('pages.contact');
});

Route::post('/contact-us', function () {

$variables = request()->all();
$specific = request()->input('title', 'default');

$title = "new post";
$body = "new body";
$excerpt = "Lorem ipsum";
$img = "img/newbilde.jpg";

// $newpost = new \App\Post;

// $newpost->title = $title;
// $newpost->body = $body;
// $newpost->body = $excerpt;
// $newpost->body = $img;

// $newpost->save();

$message = new \App\Message;
$message->name = request()->input('name');
$message->email = request()->input('email');
$message->question = request()->input('question');

$message->save();

$emailSend = true;

if($emailSend) {
return redirect('/contact-us')->with('message', "All good");
}else {
return redirect('/contact-us')->with('error', "All bad");    
}


});
