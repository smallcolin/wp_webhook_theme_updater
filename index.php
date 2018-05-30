<?php

  require __DIR__ . '/hooks/vendor/autoload.php';

  // 1. Script is run on git push 
  
  // $hookers = Hook\Hook::Github();
  // $hookers->listen('push');

  // echo $hookers->output;
  
  // 2. get theme and zip contents
  
  $zip = new ZipArchive();
  // $git_content = 'folder collected from git repo';
  $git_test_content = '../test_repo/themes/test_theme/';
  
  $theme_name = 'test_theme'; // Name of theme being updated
  $theme_zipped = 'test_theme.zip';
  $theme_dest = '../test_repo/wp_project/wp-content/';
  $res = $zip->open($theme_zipped, ZipArchive::CREATE | ZipArchive::OVERWRITE);

  if ($res === TRUE) {
    
    $allFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($git_test_content));

    // Run through all file/folders
    foreach ($allFiles as $key => $value) {
      // Check if file is a folder
      if ($value->isDir()) {
        flush();
        continue;
      }
      $filePath = $value->getRealPath();
      $relativePath = substr($filePath, strlen($git_test_content) + 5);
      $zip->addFile($filePath, $relativePath);
    }
    
    $zip->close();
    echo 'Zip-file created with folders and files.';
  }

  // 3. send zip to theme folder & unzip

  $zipped_res = $zip->open($theme_name . '.zip');
  
  if ($zipped_res) {
    echo 'ok';
    $zip->extractTo($theme_dest);  // This will be the destination for the theme folder
    $zip->close();
  } else {
    echo 'Error, something went wrong. Check this code: ' . $res;
  }
