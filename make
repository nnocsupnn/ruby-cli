<?php
/**
 * @author Niño Casupanan
 * @return void
 */
require_once "vendor/autoload.php";
init();

$args = $_SERVER['argv'];
unset($args[0]);
$type = $args[1];
$baseNs = getenv('BASE_NS');
$types = ['model', 'controller'];

if (!in_array($type, $types)) 
{
    echo 'valid param: ' . implode(', ', $types) . PHP_EOL;
    echo 'eg: php make model ModelName:table' . PHP_EOL;
    echo 'eg: php make controller ControllerName:method' . PHP_EOL;
    exit;
}

$name_table = explode(':', $args[2]);
$contents = [
    'model' => [
        'path' => 'src/Components/Models/',
        'content' => [
            '<?php' . PHP_EOL,
            'namespace ' . $baseNs . '\Models;',
            '',
            "// Model  " . $name_table[0],
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
            'namespace ' . ltrim(rtrim($baseNs, "\\"), "\\") . ';',
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