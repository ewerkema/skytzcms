<?php

if (! empty($greeting)) {
    echo "<h3>$greeting</h3>";
} else {
    echo $level == 'error' ? 'Whoops!' : 'Hallo!', "<br><br>";
}

if (! empty($introLines)) {
    echo implode("\n", $introLines), "<br><br>";
}

if (isset($actionText)) {
    echo "{$actionText}: {$actionUrl}", "<br><br>";
}

if (! empty($outroLines)) {
    echo implode("\n", $outroLines), "<br><br>";
}