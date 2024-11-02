<?php
function changeIcon($iconFilePath)
{
   $destination_path = 'C:/xampp/htdocs/favicon.ico';
   if (copy($iconFilePath, $destination_path)) {
      echo "<script>console.log('Set website icon');</script>";
   } else {
      echo "<script>console.log('Failed to set website icon');</script>";
   }
}