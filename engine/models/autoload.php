<?php
spl_autoload_register(function($className) {
  $className = ltrim($className, '\\');            //  \Anu\blah\blah  --> Anu\blah\blah
  $firstSlash = strpos($className, '\\');          //  get position of first \
  $firstNs = substr($className,0,$firstSlash);     //  get "Anu"
  $remaining = substr($className, $firstSlash+1);  //  get "blah\blah"
  if ($firstNs == APPNAMESPACE) {
    $filename = __DIR__.DS."$remaining.php";
    if (file_exists($filename)) { require $filename; return true; }
  }
  return false;
});