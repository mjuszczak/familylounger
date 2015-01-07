<?php

$postId = $post['Post']['id'];
$postTitle = $post['Post']['title'];
$postAuthor = $post['User']['first_name'] . " " . $post['User']['last_name'];
$postAuthorEmail = $post['User']['email'];
$postDate = $this->Time->niceShort($post['Post']['created']);
$postContent = $post['Post']['contents'];
$attachments = Hash::combine($post, 'PostAttachment.{n}.id', 'PostAttachment.{n}.description');

$this->extend("/Common/lounge_area");
$this->start('raw-title'); ?>
  <h2><?php echo $postTitle; ?></h2>
  <?php if(in_array($userRole, array('admin'))) { ?>
    &#x2015;
    <a href="/posts/edit/<?php echo $postId; ?>">&#x270E; Edit</a> |
    <?php
      echo $this->Html->link(
        "✗ Delete",
        "/posts/delete/${postId}",
        false,
        "Click OK to confirm you would like to delete this post."
      );
    ?>
  <?php } ?>
<?php $this->end(); ?>

<div id="blog">
  <article class="post">
    <div id="meta">
      <div class="gravatar">
        <?php echo $this->Gravatar->image($postAuthorEmail,array('s' => '20')); ?>
      </div>
      <p class="info">Authored by <?php echo $postAuthor; ?> on <?php echo $postDate; ?></p>
    </div>
    <?php if(!empty($attachments)) { ?>
    <div class="attachments">
      <ul>
      <?php foreach($attachments as $id => $description) { ?>
        <li>
          <a data-lightbox="attachments" title="<?php echo $description; ?>" href="/posts/attachment/<?php echo $id; ?>">
            <img src="/posts/attachment/<?php echo $id; ?>" />
          </a>
        </li>
      <?php } ?>
      </ul>
    </div>
    <?php } ?>
    <p class="content">
      <?php echo $postContent; ?>
    </p>
    <div id="comments" class="comments">
      <div class="comment-count">
        <h2><?php echo count($comments); ?> comment(s)</h2>
      </div>
      <div class="comment-list">
        <?php foreach($comments as $comment) {
          $commentId = $comment['Comment']['id'];
          $commentAuthor = $comment['User']['first_name'] . " " . $comment['User']['last_name'];
          $commentAuthorEmail = $comment['User']['email'];
          $commentContent = $comment['Comment']['contents'];
          $commentDate = $this->Time->timeAgoInWords(
            $comment['Comment']['created'],
            array('format' => 'F jS, Y'));
        ?>
        <div id="<?php echo "comment-$commentId"; ?>" class="comment">
          <div class="author-gravatar">
            <?php echo $this->Gravatar->image(
              $commentAuthorEmail,
              array('s' => 40));
            ?> 
          </div>
          <div class="comment-data">
            <div class="author-date">
              <span class="author"><?php echo $commentAuthor; ?></span>
              <span class="comment-date"><?php echo $commentDate; ?></span>
            </div>
            <p><?php echo $comment['Comment']['contents']; ?></p>
          </div>
          <div class="clear"></div>
        </div> <!-- /.comment -->
        <?php } ?>
      </div> <!-- /.comment-list -->
      <?php if($userRole != 'anon') { ?>
      <div class="reply">
        <?php echo $this->Form->create(array(
          'action' => "/comments/add/$postId",
          'inputDefaults' => array(
              'label' => false
          )
        ));
        echo $this->Form->input('Comment.contents',array(
          'type' => 'textarea',
          'placeholder' => 'Leave a comment...'
        ));
        echo $this->Form->button('Save',array(
          'class' => 'button'
        ));
        echo $this->Form->button('Reset',array(
          'type' => 'reset',
          'class' => 'button'
        ));
        echo $this->Form->end();
        ?>
      </div> <!-- /.reply -->
      <?php } ?>
    </div>
  </article>
</div><!-- /#blog -->
