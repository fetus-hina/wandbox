#!/usr/bin/env php
<?php
$realBase = '/opt/hina';
$envs = [
    // "jail name"      => "directory"
    "arch"              => "{$realBase}/arch",
    "debian-sid"        => "{$realBase}/debian-sid",
    "debian6"           => "{$realBase}/debian6",
    "debian7"           => "{$realBase}/debian7",
    "debian8"           => "{$realBase}/debian8",
    "el5"               => "{$realBase}/centos5",
    "el5php53"          => "{$realBase}/centos5-53",
    "el6"               => "{$realBase}/centos6",
    "fc22"              => "{$realBase}/fedora22",
    "fc23"              => "{$realBase}/fedora23",
    "fc24"              => "{$realBase}/fedora24",
    "fcrh"              => "{$realBase}/fedora-rawhide",
    "gentoo"            => "{$realBase}/gentoo",
    "suse-leap42-1"     => "{$realBase}/suse-leap42-1",
    "suse-tumbleweed"   => "{$realBase}/suse-tumbleweed",
    "ubuntu1404"        => "{$realBase}/ubuntu1404",
    "ubuntu1510"        => "{$realBase}/ubuntu1510",
    "ubuntu1604"        => "{$realBase}/ubuntu1604",
    "ubuntu1610"        => "{$realBase}/ubuntu1610",
    "ubuntu-testing"    => "{$realBase}/ubuntu-testing",
];

// -------------------------------------------------------------------------------

$roMounts = [
    '/bin'                  => '{base}/bin',
    '/lib'                  => '{base}/lib',
    '/lib64'                => '{base}/lib64',
    '/usr'                  => '{base}/usr',
    '/etc'                  => '{base}/etc',
];

$rwMounts = [
   '/tmp'                   => './jail/tmp',
   '/var/tmp'               => './jail/tmp',
   '/home/jail'             => './store',
];

// -------------------------------------------------------------------------------

$ret = (object)[
    'jail' => (object)[
    ],
];

foreach ($envs as $jailName => $baseDir) {
    $jail = (object)[
        'jail-command' => [
            '/usr/bin/env',
            'HOME=/home/jail',
            '/usr/bin/nice',
            '@bindir@/prlimit',
            '--core=0',
            '--as=1073741824',
            '--cpu=30',
            '--data=536870912',
            '--fsize=134217728',
            '--nofile=256',
            '--nproc=256',
            '--',
            '@bindir@/cattlegrid',
            '--rootdir=./jail',
            '--mount=' . mounts($baseDir, $roMounts),
            '--rwmount=' . mounts($baseDir, $rwMounts),
            '--devices=/dev/null,/dev/zero,/dev/full,/dev/random,/dev/urandom',
            '--chdir=/home/jail',
            '--',
        ],
        "program-duration" => 60,
        "compile-time-limit" => 60,
        "kill-wait" => 5,
        "output-limit-kill" => 262144,
        "output-limit-warn" => 131072,
    ];
    $ret->jail->{$jailName} = $jail;
}

echo json_encode($ret, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
exit;

function mounts($baseDir, array $mounts)
{
    $data = [];
    foreach ($mounts as $path => $realPath) {
        $realPath = str_replace('{base}', $baseDir, $realPath);
        if (substr($realPath, 0, 1) === '/' && !file_exists($realPath)) {
            echo "{$realPath} does not exist.\n";
            continue;
        }
        $data[] = $path . '=' . $realPath;
    }
    return implode(',', $data);
}
