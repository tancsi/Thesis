 <?php


    if (is_uploaded_file($_FILES['bill']['tmp_name'])) {
    $uploads_dir = 'upload/';
                            $tmp_name = $_FILES['bill']['tmp_name'];
                            $pic_name = $_FILES['bill']['name'];
                            move_uploaded_file($tmp_name, $uploads_dir.$pic_name);
                            }
               else{
                   echo "File not uploaded successfully.";
           }

   ?>