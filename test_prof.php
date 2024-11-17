<div class="container main-content">
    <!-- Profile Picture and Name -->
    <div class="profile-container">
       <div class="profile-picture-container text-center">
           <img src="<?php echo !empty($student['profile_picture']) ? htmlspecialchars($student['profile_picture']) : 'default-profile.png'; ?>" 
                alt="Profile Picture" 
                class="rounded-circle" 
                width="150" height="150">
           
           <!-- Camera icon with upload link -->
           <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data" style="position: relative; display: inline-block;">
               <label for="profilePictureUpload" class="camera-icon" style="position: absolute; top: 70%; left: 75%; cursor: pointer;">
                   <i class="fas fa-camera" style="font-size: 24px;"></i>
               </label>
               <input type="file" name="profile_picture" id="profilePictureUpload" style="display: none;" onchange="this.form.submit();">
               <input type="hidden" name="student_id" value="<?php echo $studentID; ?>">
           </form>
       </div>

       <!-- Optional placeholder icon if profile picture is not available -->
       <div class="profile-pic">
           <i class="fas fa-user"></i>
       </div>
    </div>
</div>


<style>
    .profile-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2em;
            color: #333;
            margin-right: 20px;
        }
</style>