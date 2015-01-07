<?php

$this->extend('/Common/lounge_area');

$this->start('raw-title'); ?>
  <h2>Blog</h2>
  <?php if(in_array($userRole, array('admin'))) { ?>
    &#x2015;
    <a href="/posts/add">&#x271A; New Post</a>
  <?php } ?>
<?php $this->end();
?>

<div id="blog">
<?php if(!count($posts)){ ?>
  <div class="empty">
    <p>No posts have been written yet</p>
  </div>
<?php } ?>
<?php foreach($posts as $post) {
  $postId = $post['Post']['id'];
  $postTitle = $post['Post']['title'];
  $author = $post['User']['full_name'];
  $postedOn = $this->Time->format(DEFAULT_DATE_FORMAT,$post['Post']['created']);
  $content = $post['Post']['contents'];
  $commentCount = count($post['Comment']);
  ?>
  <article class="postsummary">
    <div class="dateboxwrap">
      <div class="datebox">
        <?php echo $this->Time->format("M d",$post['Post']['created']); ?>
      </div>
      <div class="year">
        <?php echo $this->Time->format("Y",$post['Post']['created']); ?>
      </div>
    </div><!-- /.dateboxwrap -->
    <div class="post">
      <div class="title">
        <a href="/posts/view/<?php echo $postId; ?>">
          <strong><?php echo $postTitle; ?></strong>
        </a>
      </div>
      <?php echo strlen($content) > 180 ? substr(strip_tags($content), 0, 180) . " ..." : $content; ?>
    </div><!-- /.message -->
    <div class="comments">
      <a href="/posts/view/<?php echo $postId; ?>#comments" rel="noreferrer">Comments</a>
      <?php echo "[$commentCount]"; ?>
    </div><!-- /.comments -->
    <div class="clear"></div>
  </article>
<?php } ?>
<?php if($this->Paginator->counter('{:pages}') > 1) { 
  echo $this->element('Pagination/default');
} ?>
</div><!-- /#blog -->
