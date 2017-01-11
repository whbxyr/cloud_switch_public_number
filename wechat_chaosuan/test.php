<?php
$test = md5(md5('test', true));
echo mb_strlen($test);
?>