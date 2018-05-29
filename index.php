<?php

  // use hooks; // Needs proper namespace from jakobs library
 
  $zip = new ZipArchive();

  // 1. Script is run on git push 

  // $hookers = Hook\Hook::Github();
  // $hookers->listen('push');

  // echo $hookers->output;

  // 2. get theme and zip contents

  // $git_content = 'folder collected from git repo';
  $git_test_content = '../test_repo/themes/test_theme/';
  $test = scandir($git_test_content);
  var_dump($test);
  
  $theme_name = 'test_theme'; // Name of theme being updated
  $theme_zipped = 'test_theme.zip';
  $res = $zip->open($theme_zipped, ZipArchive::CREATE | ZipArchive::OVERWRITE);

  if ($res === TRUE) {
    // addGlob()

    $options = array('add_path' => 'themes/' . $theme_name . '/', 'remove_all_path' => TRUE);
    $zip->addGlob($git_test_content . '*.{jpg,php,txt}', GLOB_BRACE, $options);  // $git_content variable will be used here.
    // $zip->addFromString('test_folder/test.txt', 'file content goes here');  // adding a file with some text
    $zip->close();
    echo 'Zip file created. Good job!';
  } else {
    echo 'That didn\'t work.';
  }

  // 3. send zip to theme folder & unzip

  $zipped_res = $zip->open($theme_name . '.zip');
  
  if ($zipped_res) {
    echo 'ok';
    $zip->extractTo('../test_repo/wp_project/wp-content/');  // This will be the destination for the theme folder
    $zip->close();
  } else {
    echo 'Error, something went wrong. Check this code: ' . $res;
  }
