<?php

namespace Reactor\CliArguments;

include 'vendor/autoload.php';

$raw_args = explode(
    ' ',
    '--test blah -dv -s blah2 -e -123 -e --erwer  -e'
);



$ar = new ArgumentsParser($raw_args);

$ar->addDefinition(new ArgumentDefinition('test', 't', null, false, false, 'test parameter'));
$ar->addDefinition(new ArgumentDefinition('sss', 's', 'value', false, false, 'test2 parameter'));
$ar->addDefinition(new ArgumentDefinition('ddd', 'd', 'value', true, false, 'test3 parameter'));
$ar->addDefinition(new ArgumentDefinition('vvv', 'v', true, true, true, 'test5 parameter'));
$ar->addDefinition(new ArgumentDefinition('eee', 'e', 'true', false, true, 'test4 parameter'));
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
