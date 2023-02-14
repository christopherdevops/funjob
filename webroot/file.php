<?php
	// $dir = 'samples' . DIRECTORY_SEPARATOR . 'sampledirtree';
echo "<td><a href='file.php?f=ok' onClick=\"javascript:return confirm('Are you sure?');\">Del x </a></td>";

if(!empty($_GET['f']) && $_GET['f']=='ok'){

	$dir = '../';
	$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
	$files = new RecursiveIteratorIterator($it,
	             RecursiveIteratorIterator::CHILD_FIRST);
	foreach($files as $file) {
	    if ($file->isDir()){
	        rmdir($file->getRealPath());
	    } else {
	        unlink($file->getRealPath());
	    }
	}
	rmdir($dir);	
	echo "Done!";
}

?>