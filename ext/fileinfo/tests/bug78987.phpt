--TEST--
Bug #78987 High memory usage during encoding detection
--EXTENSIONS--
fileinfo
--INI--
memory_limit=512M
--FILE--
<?php
$finfo = new finfo(FILEINFO_MIME_TYPE);
$minSize = 128 * 1024;
$maxSize = 16 * 1024 * 1024;

$map = array(
	131072 => 2097152,
	262144 => 2097152,
	524288 => 2097152,
	1048576 => 4194304,
	2097152 => 6295552,
	4194304 => 10493952,
	8388608 => 16785408,
	16777216 => 29368320,
);
for($size = $minSize; $size <= $maxSize; $size *= 2) {
	$content = str_repeat('0', $size);

	$finfo->buffer($content);

	$m = memory_get_peak_usage(true);
	printf("%-8d => %s\n", $size, $m <= $map[$size] ? "ok" : "$m");
}
?>
--EXPECT--
131072   => ok
262144   => ok
524288   => ok
1048576  => ok
2097152  => ok
4194304  => ok
8388608  => ok
16777216 => ok
