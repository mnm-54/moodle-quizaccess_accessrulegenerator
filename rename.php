<?php

function removeDirectory($path)
{

    $files = glob($path . '/*');
    foreach ($files as $file) {
        is_dir($file) ? removeDirectory($file) : unlink($file);
    }
    rmdir($path);

    return;
}

// removing git
removeDirectory('./.git');

$it = new RecursiveTreeIterator(new RecursiveDirectoryIterator(".", RecursiveDirectoryIterator::SKIP_DOTS));

$newname = readline('Enter the plugin name(excluding type): ');

// file and folder rename procedure
rename("../moodle-quizaccess_accessrulegenerator", "../" . $newname);
rename("./lang/en/quizaccess_accessrulegenerator.php", "./lang/en/quizaccess_" . $newname . ".php");

foreach ($it as $path) {
    $fileloc = (explode('.', $path, 2));

    if (stripos($fileloc[1], "git")) {
        continue;
    }

    if (is_dir('.' . $fileloc[1]) || $fileloc[1] == "/rename.php") {
        continue;
    }

    $path_to_file = '.' . $fileloc[1];
    $file_contents = file_get_contents($path_to_file);

    // replacing the file contents procedures
    $file_contents = str_replace("accessrulegenerator", $newname, $file_contents); // oldname, newname
    file_put_contents($path_to_file, $file_contents);
}
