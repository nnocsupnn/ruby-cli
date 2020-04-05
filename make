<?php
/**
 * php make model ModelName:infoUser
 * php make controller ControllerName
 */

require_once "vendor/autoload.php";
init();

$args = $_SERVER['argv'];
$newVars = $args;

unset($newVars[0]);
$type = $newVars[1];
$types = ['model', 'controller'];
if (!in_array($type, $types)) 
{
    echo 'valid param: ' . implode(', ', $types) . PHP_EOL;
    echo 'eg: php make model ModelName:table' . PHP_EOL;
    echo 'eg: php make controller ControllerName:method' . PHP_EOL;
    exit;
}

// if (!@$newVars[2] && $type == 'model') die('Please provide option modelname and table eg. ModelName:table');
$name_table = explode(':', $newVars[2]);

$contents = [
    'model' => [
        'path' => 'src/Components/Models/',
        'content' => [
            '<?php' . PHP_EOL,
            'namespace  Ruby\Components\Models;',
            '',
            '/* Model */',
            '',
            'use Illuminate\Database\Eloquent\Model;' . PHP_EOL,
            '',
            'class ' . $name_table[0] . ' extends Model {',
            '',
            "\tprotected \$table = '" . @$name_table[1] . "';",
            '',
            "\tprotected \$timestamps = false;",
            '}'
        ]
    ],
    'controller' => [
        'path' => 'src/Components/',
        'content' => [
            '<?php' . PHP_EOL,
            '',
            'namespace  Ruby\Components;',
            '',
            '/* Controller */',
            '',
            'class ' . @$name_table[0] . ' {',
            '',
            "\tpublic function " . @$name_table[1] . "() {",
            '',
            "\t\tdd(__FILE__);",
            '',
            "\t}",
            '}'
        ]
    ]
];

$os_type = PHP_OS;
if ($os_type == 'WINNT') 
{
    $cmd = 'type nul > ' . $contents[$type]['path'] . $name_table[0] . '.php';
} 
else 
{
    $cmd = 'touch ' . $contents[$type]['path'] . $name_table[0] . '.php';
}

$fname = $contents[$type]['path'] . $name_table[0] . '.php';
exec($cmd);

if (file_exists($fname)) 
{
    file_put_contents($fname, implode(PHP_EOL, $contents[$type]['content']), FILE_APPEND);
} 
else 
{
    die('File not created.');
}

die('Done.');