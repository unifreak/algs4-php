<?php
function readString()
{
    $c = fgetc(STDIN);
    // skip pre whitespace
    while (($c == " " || $c == "\t")) {
        $c = fgetc(STDIN);
    }
    var_dump("prev got $c;");

    $s = $c;
    $c = fgetc(STDIN);
    while (strcmp($c, ' ') != 0 && strcmp($c, "\t") != 0) {
        var_dump("tail got:");
        var_dump($c.";");
        var_dump("strcmp space:" . strcmp(' ', $c));
        var_dump("strcmp tab:" . strcmp("\t", $c));
        var_dump("equal empty string: " . ($c == ' '));
        var_dump("equal line feed: " . ($c == PHP_EOL));
        if ($c == PHP_EOL) {
            fseek(STDIN, -1, SEEK_CUR);
            var_dump("breaking");
            break;
        }
        $s .= $c;
        $c = fgetc(STDIN);
    }

    var_dump("read string: $s}");
    return $s;
}

while (! feof(STDIN)) {
    var_dump(readString());
}