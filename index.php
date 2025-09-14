<?php
session_start();
include "partials/header.php";
// include "partials/notifications.php";
include "config/Database.php";
include "classes/Task.php";
//creating database object and establishing the connection with database
$database=new Database();
$db=$database->connect();

//creating and using task object and passing required
//data for adding ,deleting tasks,..,etc
$todo=new Task($db);

 
if($_SERVER["REQUEST_METHOD"]==="POST"){
  if(isset($_POST['add_task'])){
    $todo->task=$_POST['task'];

    $result=$todo->create();
    //we are getting array so we can
    //check if success key is set to true then task i added
    if($result['success']===true){
    $_SESSION['message']="{$result['message']}";
    $_SESSION['msg_type']="success";
    }
    else{
        //if success key is false then we have
        //error which we can get using error key along with $result
        $_SESSION['message']="Error occured : ".$result['error'];
        $_SESSION['msg_type']="error";
    }
  }
  else if(isset($_POST['complete_task']))
  {
    $todo->complete($_POST['id']);
    $_SESSION['message']="Task Marked completed";
    $_SESSION['msg_type']="success";
  }
  else if(isset($_POST['undo_complete_task']))
  {
    $todo->undoComplete($_POST['id']);
    $_SESSION['message']="Task Marked incompleted";
    $_SESSION['msg_type']="success";
  }
  else if(isset($_POST['delete_task']))
  {
    // var_dump($_POST['id']);
    $todo->delete($_POST['id']);
    $_SESSION['message']="Task deleted";
    $_SESSION['msg_type']="success";
  }
}

//fetch the task
$tasks=$todo->read()
?>
<!-- Notification container -->
 <?php if(isset($_SESSION['message'])):?>
<div class="notification-container <?php  echo  isset($_SESSION['message'])?'show':''; ?>">
    <div class="notification <?php if(isset($_SESSION['msg_type'])) echo  $_SESSION['msg_type'] ?>">
    <?php  echo $_SESSION['message'];?>
    <?php    unset($_SESSION['message']);?>
    </div>
</div>
    <?php endif;?>


<!-- Main Content Container -->
<div class="container">
    <h1>Todo App</h1>

    <!-- Add Task Form -->
    <form method="POST">
        <input type="text" name="task" placeholder="Enter a new task" required>
        <button type="submit" name="add_task">Add Task</button>
    </form>

    <!-- Display Tasks -->
    <ul>
        <!-- For displaying task we are using php while loop -->
        <?php while($task=$tasks->fetch_assoc()):?>
            <!-- Displaying only two buttons at a time with php conditions -->
        <li class="completed">
            <!-- span is showing the name of the task -->
             <!-- dynamically using the css class completed with help of ternary operator -->
            <span class="<?php echo $task['is_completed']?'completed':''; ?>"><?php echo $task['task']; ?></span>
            <div>
                <?php if(!$task['is_completed']): ?>

            <!-- in both complete an undo we are using task id from the table
            to deal with the respective task. 
            -->
                <!-- Complete Task -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $task['id'];?>">
                    <button class="complete" type="submit" name="complete_task">Complete</button>
                </form>
                <?php else: ?>
                <!-- Undo Completed Task -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $task['id'];?>">
                    <button class="undo" type="submit" name="undo_complete_task">Undo</button>
                </form>
                <?php  endif; ?>
                <!-- Delete Task -->
                <form onsubmit="return confirmDelete()" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $task['id'];?>">
                    <button class="delete" type="submit" name="delete_task">Delete</button>
                </form>
               
            </div>
        </li>
 
        <?php endwhile; ?>
    </ul>
</div>
<script>
    function confirmDelete()
    {
        return confirm("Are you sure you want to delete?")
    }
</script>
<?php
include "partials/footer.php";
?>
