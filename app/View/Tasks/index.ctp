<?php

$this->extend('/Common/lounge_area');
$this->start('raw-title'); ?>
  <h2>Tasks</h2>
  <?php if(in_array($userRole, array('admin'))) { ?>
    &#x2015;
    <a href="/tasks/add">&#x271A; New Task</a>
  <?php } ?>
<?php $this->end();

echo $this->Html->script('tasks', array('inline' => false));
?>

<div id="tasks">
<?php if(!count($tasks)){ ?>
  <div class="empty">
    <p>No tasks added yet.</p>
  </div>
<?php } ?>
<?php foreach($tasks as $task) {
  $id = $task['Task']['id'];
  $descr = $task['Task']['description'];
  $isCompleted = $task['Task']['completed'];
  $completedOn = date('M j, g:i a', strtotime($task['Task']['updated']));
  ?>
  <div class="task">
    <div class="input">
      <input type="checkbox" id="task-<?php echo $id; ?>" data-id="<?php echo $id; ?>" <?php if($isCompleted) { echo "checked"; } ?>/>
      <label for="task-<?php echo $id; ?>">
        <span></span>
        <?php echo $descr; ?>
      </label>
    </div>
    <div class="meta">
      <div class="float-left actions">
        <?php echo $this->Html->link('Edit', "/tasks/edit/${id}"); ?> |
        <?php echo $this->Html->link('Delete', "/tasks/delete/${id}", false, 'Click OK to confirm you would like to delete this task.'); ?>
      </div>
      <div class="float-right completed">
        <?php if($isCompleted) { ?>
          Completed <?php echo $completedOn; ?>
        <?php } ?>
      </div>
      <div class="clear"></div>
    </div>
  </div>
<?php } ?>
<?php if($this->Paginator->counter('{:pages}') > 1) {
  echo $this->element('Pagination/default');
} ?>
</div> <!-- /tasks -->
