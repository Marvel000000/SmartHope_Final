<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
}
if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->execute([$tutor_id]);
$total_contents = $select_contents->rowCount();

$select_blog = $conn->prepare("SELECT * FROM `blog` WHERE tutor_id = ?");
$select_blog->execute([$tutor_id]);
$total_post = $select_blog->rowCount();

$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->execute([$tutor_id]);
$total_playlists = $select_playlists->rowCount();

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$select_likes->execute([$tutor_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_comments->execute([$tutor_id]);
$total_comments = $select_comments->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/style2.css">
   <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<?php include '../components/header2.php'; ?>

<!-- quotes section -->
<section class="about">

   <div class="row">

      <div class="image">
         <img src="../images/about-img.svg" alt="">
      </div>

      <div class="content">
      <div class="content">
  <h3>Quotes</h3>
  <p id="quote">Loading...</p>
  <a href="#" class="inline-btn" id="next-quote-btn">Next Quotes</a>
</div>

<script>
  // Function to fetch a new quote
  function fetchNewQuote() {
    // Make a request to the quote API
    fetch('https://api.quotable.io/random')
      .then(response => response.json())
      .then(data => {
        var quote = data.content;
        var author = data.author;

        // Update the quote element with the new quote
        document.getElementById('quote').textContent = `"${quote}" - ${author}`;
      })
      .catch(error => console.log(error));
  }

  // Function to handle the "Get Started" button click event
  function handleNextQuote() {
    fetchNewQuote();
  }

  // Call the fetchNewQuote function initially to display the first quote
  fetchNewQuote();

  // Add event listener to the "Get Started" button to trigger the next quote
  document.getElementById('next-quote-btn').addEventListener('click', handleNextQuote);
</script>


   </div>

</section>
   
<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Welcome!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="profile.php" class="btn">View Profile</a>
      </div>

      <div class="box">
         <h3><?= $total_playlists; ?></h3>
         <p>Total Playlists</p>
         <a href="add_course.php" class="btn">Add new Courses</a>
      </div>

      <div class="box">
         <h3><?= $total_contents; ?></h3>
         <p>Total Contents</p>
         <a href="add_content.php" class="btn">Add new content</a>
      </div>

      <div class="box">
         <h3><?= $total_post; ?></h3>
         <p>Total Blog Post</p>
         <a href="add_post.php" class="btn">Add new post</a>
      </div>

      <div class="box">
         <h3><?= $total_comments; ?></h3>
         <p>Total Comments</p>
         <a href="comments.php" class="btn">View comments</a>
      </div>


   </div>

</section>
<section class="courses">

   <h1 class="heading">all courses</h1>

   <div class="box-container">

      <?php
         $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC");
         $select_courses->execute(['active']);
         if($select_courses->rowCount() > 0){
            while($fetch_course = $select_courses->fetch(PDO::FETCH_ASSOC)){
               $course_id = $fetch_course['id'];

               $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
               $select_tutor->execute([$fetch_course['tutor_id']]);
               $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box">
         <div class="tutor">
            <img src="../uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_tutor['name']; ?></h3>
               <span><?= $fetch_course['date']; ?></span>
            </div>
         </div>
         <img src="../uploaded_files/<?= $fetch_course['thumb']; ?>" class="thumb" alt="">
         <h3 class="title"><?= $fetch_course['title']; ?></h3>
         <a href="course.php?get_id=<?= $course_id; ?>" class="inline-btn">View Course</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No courses added yet!</p>';
      }
      ?>

   </div>

</section>

<script src="../js/script2.js"></script>

</body>
</html>