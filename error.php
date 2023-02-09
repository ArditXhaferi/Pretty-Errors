<?php
    global $wp_version;
    $error_array = error_get_last();
    $file = file_get_contents($error_array['file']);
    $file_content_lines = file($error_array['file'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines_regex = '/^#\d+.*$/m';
    $exceptions = [
        E_ERROR => "Fatal Error",
        E_WARNING => "Warning",
        E_PARSE => "Parse Error",
        E_NOTICE => "Notice",
        E_CORE_ERROR => "Core Fatal Error",
        E_CORE_WARNING => "Core Warning",
        E_COMPILE_ERROR => "Compile Error",
        E_COMPILE_WARNING => "Compile Warning",
        E_USER_ERROR => "User-generated Fatal Error",
        E_USER_WARNING => "User-generated Warning",
        E_USER_NOTICE => "User-generated Notice",
        E_STRICT => "Strict Notice",
        E_RECOVERABLE_ERROR => "Recoverable Error",
        E_DEPRECATED => "Deprecated Error",
        E_USER_DEPRECATED => "User-generated Deprecated Error",
        E_ALL => "All Errors"
    ];
    $error_type_text = $exceptions[$error_array["type"]];
    preg_match_all($lines_regex, $error_array['message'], $lines);

    $errorMessage = $error_array['message'];

    // Error Type
    $errorType = substr($errorMessage, 0, strpos($errorMessage, ":"));

    // Error Title
    $errorTitle = substr($errorMessage, strpos($errorMessage, ":") + 1, strpos($errorMessage, " in") - strpos($errorMessage, ":") - 1);
    $errorTitle = trim(strstr($errorTitle, ' '));
    $error_array['line'] = $error_array['line'] - 1;
    ?>

<html class="bg-gray-300 w-full py-12">
<head>
    <script src="https:cdn.tailwindcss.com"></script>
</head>
    <body class="flex items-center w-full flex-col">
        <div class='bg-white p-8 w-[90%] shadow-lg mb-10'>
            <div class="w-full flex justify-between items-center">
                <span class="py-1 text-lg px-4 items-center flex gap-3 rounded-sm bg-gray-100 w-fit capitalize"><?= $error_type_text ?></span>
                <div class='flex'>
                    <span class='text-sm text-gray-500 mr-4'>PHP <?= phpversion() ?></span>
                    <span class='text-sm text-gray-500 flex'>
                        <img class='mr-2' width='16px' src='https:static-00.iconduck.com/assets.00/wordpress-icon-512x512-38lz8224.png' />
                        <?= $wp_version ?>
                    </span>
                </div>
            </div>
            <h1 class='font-semibold text-xl leading-slug mt-6 mb-4'><?= $errorTitle ?></h1>
        </div>
        <div class='bg-white flex w-[90%] shadow-lg'>
            <div class='flex flex-col w-[30%]'>
                <div class='px-6 py-4 border-b break-all border-gray-200 bg-blue-400 text-white'>
                    <?= str_replace(ABSPATH, "", $error_array['file']) ?>: <?= $error_array['line'] + 1 ?> <br> <b> <?= $errorTitle ?></b>
                </div>
                <?php foreach($lines[0] as $line){ ?>
                    <div class='px-6 py-4 border-b break-all border-gray-200 hover:bg-blue-400 hover:text-white'>
                        <?= str_replace(ABSPATH, "", substr(explode("):", $line)[0], 3)) ?>): <br>
                        <b> <?= explode("):", $line)[1] ?> </b>
                    </div>
                <?php } ?>
            </div>
            <div class='w-full overflow-scroll border-l border-gray-200 flex w-[70%]  mask-fade-r'>
                <div class="py-8 flex flex-col max-w-[35px]">
                    <p class="px-2 font-mono leading-loose select-none">
                        <?php foreach($file_content_lines as $index => $line){ ?>
                            <span class="text-gray-500 <?= (int)$error_array['line'] == $index ? 'font-semibold text-gray-800' : '' ?>"><?= $index + 1 ?></span>
                        <?php } ?>
                    </p>
                </div>
                <div class='py-8 w-full'>
                    <?php foreach($file_content_lines as $index => $line){ ?>
                        <p class="px-4 whitespace-nowrap leading-[28px] hover:bg-blue-100 <?= (int)$error_array['line'] == $index ? 'bg-blue-100' : '' ?>"><?= str_replace([' ', "\t"], ['&nbsp;', '&nbsp;&nbsp;'], htmlspecialchars($line)) ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
    <style>
        .mask-fade-r{
            -webkit-mask-image: linear-gradient(90deg,#000 0,#000 calc(100% - 4rem),transparent calc(100% - 2rem));
        }
    </style>
</html>