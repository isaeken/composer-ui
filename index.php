<?php

require_once __DIR__ . '/vendor/autoload.php';

$installer_options = [
    'dry_run' => ['Dry Run', false],
    'verbose' => ['Verbose', false],
    'prefer_source' => ['Prefer Source', false],
    'prefer_dist' => ['Prefer Dist', true],
    'dev_mode' => ['Dev Mode', false],
    'dump_autoloader' => ['Dump Autoloader', false],
    'optimize_autoloader' => ['Optimize Autoloader', true],
    'class_map_authoritative' => ['Class Map Authoritative', false],
    'apcu_autoloader' => ['Apcu Autoloader', false],
    'ignore_platform_requirements' => ['Ignore Platform Requirements', false],
];

if (isset($_GET['action']) && isset($_GET['cwd'])) {
    chdir($_GET['cwd']);

    switch ($_GET['action']) {
        case 'install':
            $installer = \IsaEken\ComposerUI\Installer::getInstance();
            foreach ($installer_options as $option => $arr) {
                $installer->$option = isset($_GET[$option]);
            }
            $output = $installer->setIniForHttp()->run();
            break;
        default:
            $output = 'Unkown command.';
            break;
    }
}
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Composer UI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.0-canary.14/tailwind.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/2.3.0/alpine.js"></script>
</head>
<body>
<div x-data="{ action: 'install' }" class="w-full max-w-2xl mx-auto my-6">
    <form action="/" method="get" id="form" class="space-y-4">
        <div>
            <label for="cwd">Working Directory</label>
            <input class="block w-full bg-gray-50 border border-gray-200 rounded py-2 px-3" type="text" id="cwd" name="cwd" value="<?= getcwd(); ?>">
        </div>
        <div>
            <label for="action">Action</label>
            <select @change="action = $event.target.value" class="block w-full bg-gray-50 border border-gray-200 rounded py-2 px-3" name="action" id="action">
                <option x-bind:value="'install'" value="install">Install</option>
            </select>
        </div>
        <div>
            <div x-show="action == 'install'">
                <?php foreach ($installer_options as $key => $value): ?>
                <div>
                    <label class="select-none" for="<?= $key; ?>"><?= $value[0]; ?></label>
                    <input type="checkbox" id="<?= $key; ?>" name="<?= $key; ?>" <?= $value[1] ? 'checked' : '' ?>>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div>
            <button type="submit" class="block w-full bg-gray-50 border border-gray-200 rounded py-2 px-3 transition hover:bg-gray-100">
                Submit
            </button>
        </div>
        <?php if (isset($output)): ?>
        <div class="mt-4 p-2 bg-black text-white overflow-x-auto"><pre><?= $output; ?></pre></div>
        <?php endif; ?>
    </form>
</div>
</body>
</html>
