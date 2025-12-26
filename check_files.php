<?php
echo "<h3>Current Folder (aquatics):</h3>";
echo "<pre>";
print_r(scandir('.'));
echo "</pre>";

echo "<h3>Inside 'src' folder:</h3>";
if (is_dir('src')) {
    echo "<pre>";
    print_r(scandir('src'));
    echo "</pre>";
} else {
    echo "<h4 style='color:red'>ERROR: The 'src' folder does not exist!</h4>";
}
?>