<?php

namespace Webreactor\CliArguments;

include 'vendor/autoload.php';

$raw_args = explode(
    ' ',
    '--test blah -dv -s blah2 -e -123 -e --erwer  -e'
);



$ar = new ArgumentsParser($raw_args);

$ar->addDefinition(new ArgumentDefinition('test', 't', 'test parameter', null, false, false));
$ar->addDefinition(new ArgumentDefinition('sss', 's', 'test2 parameter', 'value', false, false));
$ar->addDefinition(new ArgumentDefinition('ddd', 'd', 'test3 parameter', 'value', true, false));
$ar->addDefinition(new ArgumentDefinition('vvv', 'v', 'test5 parameter', true, true, true));
$ar->addDefinition(new ArgumentDefinition('eee', 'e', 'test4 parameter', 'true', false, true));
$ar->parse();
//print_r($ar);
$parsed = $ar->getAll();
var_export($parsed);

if (var_export($parsed, true) !== var_export(getExpected(), true)) {
    echo "\nDifferent than expected\n";
    var_export(getExpected());
    exit(1);
} else {
    echo "\nTest passed\n";
}



function getExpected() {
    return array (
        'test' => 'blah',
        'sss' => 'blah2',
        'ddd' => 'value',
        'vvv' => array (
            0 => true,
        ),
        'eee' => array (
            0 => '-123',
            1 => '--erwer',
            2 => 'true',
        )
    );
}
