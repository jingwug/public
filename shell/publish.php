<?php
$project = isset($_GET['project']) ? trim($_GET['project']) : '';
if(preg_match('/[a-z]{3,20}/', $project)) {
        $command = "source /home/www/.bash_profile; sh /home/www/publish.sh --project={$project}";
} else {
        $command = "source /home/www/.bash_profile; sh /home/www/publish.sh";
}

exec($command." --update", $outLines, $returnvar);
$out = implode($outLines, "<br/>\n");
$out = str_replace(' ', '&nbsp;', $out);
echo "SVN更新中：<br/>\n";
echo $out;
echo "<br/>\n<br/>\n<br/>\n";

$outLines = null;
exec($command, $outLines, $returnvar);
echo "rsync同步中：<br/>\n";
$out = implode($outLines, "<br/>\n");
$out = str_replace(' ', '&nbsp;', $out);
echo $out;

