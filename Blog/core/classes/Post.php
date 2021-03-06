<?php

/**
* Post
*/
class Post
{

  public static function all() {
    $articles = DB::table("articles")->orderBy('id', 'DESC')->paginate(5);

    foreach ($articles['data'] as $key => $val) {
      $articles['data'][$key]->comment_counts = DB::table('comments')->where('article_id', $val->id)->count();
      $articles['data'][$key]->like_counts = DB::table('article_likes')->where('article_id', $val->id)->count();
    }
    return $articles;
  }
  static function detail($slug) {
    $article = DB::table('articles')->where('slug', $slug)->getOne();
    $article->like_counts = DB::table('article_likes')->where('article_id', $article->id)->count();
    $article->comment_counts = DB::table('comments')->where('article_id', $article->id)->count();
    return $article;
    //echo '<pre>';
    // print_r($article);
  }
  static function category($id) {
    return DB::table('categories')->where('id', $id)->getOne();

  }
  static function comments($id) {
    $cmts = DB::table('comments')->where('article_id', $id)->get();
    //print_r($cmts);
    return $cmts;
  }
  static function user($id) {
    $users = DB::table('users')->where('id', $id)->get();
    return $users;
  }
  // Cat by slug
  static function catBySlug($slug) {
    $id = DB::table('categories')->where('slug', $slug)->getOne()->id;
    $articles = DB::table("articles")->where('category_id', $id)->orderBy('id', 'DESC')->paginate(2, "category=$slug");

    foreach ($articles['data'] as $key => $val) {
      $articles['data'][$key]->comment_counts = DB::table('comments')->where('article_id', $val->id)->count();
      $articles['data'][$key]->like_counts = DB::table('article_likes')->where('article_id', $val->id)->count();
    }
    return $articles;
  }

  static function langBySlug($slug) {

    $lang = DB::table('languages')->where('slug', $slug)->getOne();
    $id = $lang->id;
    $sql = "SELECT * FROM article_language INNER JOIN articles ON article_language.article_id=articles.id WHERE article_language.id=1";
    $articles = DB::raw($sql);
    return $articles;

  }

  static function create($request) {
    // Move File
    $img = $_FILES['image'];
    move_uploaded_file($img['tmp_name'], 'assets/imgs/'.$img['name']);

    // Insert Into DB

    $post = DB::create('articles', [
      'user_id' => User::auth()->id,
      'category_id' => $request['category'],
      'slug' => Helper::slug($request['title']),
      'title' => $request['title'],
      'content' => $request['content'],
      'img' => $img['name']
    ]);

    // Insert Langiage To Language DB
    foreach ($request['langs'] as $lang) {
      DB::create('article_language', [
        'article_id' => $post->id,
        'language_id' => $lang
      ]);
    }
  }

  static function search($search) {
    $articles = DB::table("articles")->where('title', 'like', "%$search%")->orderBy('id', 'DESC')->paginate(5);
    // die();
    foreach ($articles['data'] as $key => $val) {
      $articles['data'][$key]->comment_counts = DB::table('comments')->where('article_id', $val->id)->count();
      $articles['data'][$key]->like_counts = DB::table('article_likes')->where('article_id', $val->id)->count();
    }
    return $articles;
  }

  static function delete($slug) {
    $id = DB::table('articles')->where('slug', $slug)->getOne()->id;
    DB::delete('articles', $id);
  }
  static function edit($slug) {
    $post = DB::table('articles')->where('slug', $slug)->getOne();
    return $post;
  }

  static function updatePost($request, $slug) {
    $id = DB::table('articles')->where('slug', $slug)->getOne()->id;
    $imgName = '';
    // Move File
    $img = $_FILES['image'];
    move_uploaded_file($img['tmp_name'], 'assets/imgs/'.$img['name']);
    if (empty($img['name'])) {
      $imgName = $request['oldImg'];
    } else {
      $imgName = $img['name'];
    }
    // Insert Into DB

    $post = DB::update('articles', [
      'category_id' => $request['category'],
      'slug' => Helper::slug($request['title']),
      'title' => $request['title'],
      'content' => $request['content'],
      'img' => $imgName
    ], $id);
    Helper::redirect('index');
  }

  static function ownerPost($slug) {
    $id = User::auth()->id;
    $articles = DB::table("articles")->where('user_id',$id)->orderBy('id', 'DESC')->paginate(5);

    foreach ($articles['data'] as $key => $val) {
      $articles['data'][$key]->comment_counts = DB::table('comments')->where('article_id', $val->id)->count();
      $articles['data'][$key]->like_counts = DB::table('article_likes')->where('article_id', $val->id)->count();
    }
    return $articles;
  }

}