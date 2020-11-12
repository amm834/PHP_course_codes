<?php
require_once 'inc/header.php';

if (isset($_GET['category'])) {
  $slug = $_GET['category'];
  $posts = Post::catBySlug($slug);
}elseif (isset($_GET['language'])) {
  $slug = $_GET['language'];
 $posts = Post::langBySlug($slug);
} else {
  $posts = Post::all();
}
?>

<!-- Pagination  -->
<div class="card card-dark">
  <div class="card-body">
    <a href="<?php echo $posts['prev_page']; ?>" class="btn btn-danger">Prev Posts</a>
    <a href="<?php echo $posts['next_page']; ?>" class="btn btn-danger float-right">Next Posts</a>
  </div>
</div>
<!-- Post  -->
<div class="card card-dark">
  <div class="card-body">
    <div class="row">
      <!-- Loop this -->
      <?php foreach ($posts['data'] as $post): ?>
      <div class="col-md-4 mt-2">
        <div class="card">
          <img class="card-img-top"
          src="assets/imgs/<?php echo $post->img; ?>"
          alt="Card image " alt="Image">
          <div class="card-body">
            <h5 class="text-dark"><?php
              echo $post->img;
              ?></h5>
          </div>
          <div class="card-footer">
            <div class="row">
              <div
                class="col-md-4 text-center">
                <i
                  class="fas fa-heart text-warning">
                </i>
                <small
                  class="text-muted"><?php
                  echo $post->like_counts;
                  ?></small>
              </div>
              <div
                class="col-md-4 text-center">
                <i
                  class="far fa-comment text-dark"></i>
                <small
                  class="text-muted"><?php
                  echo $post->comment_counts;
                  ?></small>
              </div>
              <div
                class="col-md-4 text-center">
                <a href="detail.php?slug=<?php echo $post->slug; ?>"
                  class="badge badge-warning p-1">View</a>
              </div>
            </div>

          </div>
        </div>

      </div>
      <?php endforeach; ?>

    </div>
  </div>
</div>

<?php
require_once 'inc/footer.php';

?>