<?php
global $wp_version;
$error_array = error_get_last();
$file = file_get_contents($error_array['file']);
$file_content_lines = file($error_array['file'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$lines_regex = '/^#\d+.*$/m';
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
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

function generate_curl_command($url) {
    return "curl '" . $url . "' \\
        -X GET \\
        -H 'accept-encoding: gzip, deflate' \\
        -H 'accept-language: en-US,en;q=0.7' \\
        -H 'sec-gpc: 1' \\
        -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8' \\
        -H 'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36' \\
        -H 'upgrade-insecure-requests: 1' \\
        -H 'cache-control: max-age=0' \\
        -H 'connection: keep-alive' \\
        -H 'host: example-app.test';";
}
?>

<html class="bg-gray-300 w-full py-20">
<head>
    <script src="https:cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center w-full flex-col scrollbar-lg w-full">
<nav class="z-50 fixed top-0 h-[80px] w-full">
    <div id="nav" class="border-gray-200 h-[40px] bg-gray-300 flex justify-center items-center transition-all duration-300">
        <div class="w-[70%] flex">
            <div class="flex">
                <svg class="w-3" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="align-left"
                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="#60A5FA"
                          d="M256 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H256C273.7 32 288 46.33 288 64C288 81.67 273.7 96 256 96zM256 352H32C14.33 352 0 337.7 0 320C0 302.3 14.33 288 32 288H256C273.7 288 288 302.3 288 320C288 337.7 273.7 352 256 352zM0 192C0 174.3 14.33 160 32 160H416C433.7 160 448 174.3 448 192C448 209.7 433.7 224 416 224H32C14.33 224 0 209.7 0 192zM416 480H32C14.33 480 0 465.7 0 448C0 430.3 14.33 416 32 416H416C433.7 416 448 430.3 448 448C448 465.7 433.7 480 416 480z"></path>
                </svg>
                <p class="ml-2 font-medium text-blue-400 mr-10">STACK</p>
            </div>
            <div class="flex">
                <svg class="w-3" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="expand" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path fill="#1F2937"
                          d="M128 32H32C14.31 32 0 46.31 0 64v96c0 17.69 14.31 32 32 32s32-14.31 32-32V96h64c17.69 0 32-14.31 32-32S145.7 32 128 32zM416 32h-96c-17.69 0-32 14.31-32 32s14.31 32 32 32h64v64c0 17.69 14.31 32 32 32s32-14.31 32-32V64C448 46.31 433.7 32 416 32zM128 416H64v-64c0-17.69-14.31-32-32-32s-32 14.31-32 32v96c0 17.69 14.31 32 32 32h96c17.69 0 32-14.31 32-32S145.7 416 128 416zM416 320c-17.69 0-32 14.31-32 32v64h-64c-17.69 0-32 14.31-32 32s14.31 32 32 32h96c17.69 0 32-14.31 32-32v-96C448 334.3 433.7 320 416 320z"></path>
                </svg>
                <p class="ml-2 font-medium text-gray-800 mr-10">CONTEXT</p>
            </div>
            <div class="flex">
                <svg class="w-3" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="share" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="#1F2937"
                          d="M503.7 226.2l-176 151.1c-15.38 13.3-39.69 2.545-39.69-18.16V272.1C132.9 274.3 66.06 312.8 111.4 457.8c5.031 16.09-14.41 28.56-28.06 18.62C39.59 444.6 0 383.8 0 322.3c0-152.2 127.4-184.4 288-186.3V56.02c0-20.67 24.28-31.46 39.69-18.16l176 151.1C514.8 199.4 514.8 216.6 503.7 226.2z"></path>
                </svg>
                <p class="ml-2 font-medium text-gray-800 mr-10">SHARE</p>
            </div>
        </div>
    </div>
    <div class="border-b border-gray-200 h-10 bg-gray-100 hidden">

    </div>
</nav>
<div class='bg-white p-8 w-[70%] shadow-lg mb-10'>
    <div class="w-full flex justify-between items-center">
        <span class="py-1 text-lg px-4 items-center flex gap-3 rounded-sm bg-gray-100 w-fit capitalize"><?= $error_type_text ?></span>
        <div class='flex'>
            <span class='text-sm text-gray-500 mr-4'>PHP <?= phpversion() ?></span>
            <span class='text-sm text-gray-500 flex'>
                        <img class='mr-2' width='16px'
                             src='https:static-00.iconduck.com/assets.00/wordpress-icon-512x512-38lz8224.png'/>
                        <?= $wp_version ?>
                    </span>
        </div>
    </div>
    <h1 class='font-semibold text-xl leading-slug mt-6 mb-4'><?= $errorTitle ?></h1>
</div>
<div class='bg-white flex w-[70%] shadow-lg mb-20'>
    <div class='flex flex-col w-[30%]'>
        <div class='px-6 py-4 border-b break-all border-gray-200 bg-blue-400 text-white'>
            <?= str_replace(ABSPATH, "", $error_array['file']) ?>: <?= $error_array['line'] + 1 ?> <br>
            <b> <?= $errorTitle ?></b>
        </div>
        <?php foreach ($lines[0] as $line) { ?>
            <div class='px-6 py-4 border-b break-all border-gray-200 hover:bg-blue-400 hover:text-white'>
                <?= str_replace(ABSPATH, "", substr(explode("):", $line)[0], 3)) ?>): <br>
                <b> <?= explode("):", $line)[1] ?> </b>
            </div>
        <?php } ?>
    </div>
    <div class='w-full overflow-scroll border-l border-gray-200 flex w-[70%]  mask-fade-r'>
        <div class="py-8 flex flex-col max-w-[35px]">
            <p class="px-2 font-mono leading-loose select-none">
                <?php foreach ($file_content_lines as $index => $line){ ?>
            <p class="px-2 <?= (int)$error_array['line'] == $index ? 'font-semibold text-white-900 bg-blue-200' : '' ?>">
                <span class="text-gray-500 leading-[28px]"><?= $index + 1 ?></span>
            </p>
            <?php } ?>
            </p>
        </div>
        <div class='py-8 w-full'>
            <?php foreach ($file_content_lines as $index => $line) { ?>
                <p class="px-4 whitespace-nowrap leading-[28px] hover:bg-blue-100 <?= (int)$error_array['line'] == $index ? 'bg-blue-100' : '' ?>"><?= str_replace([' ', "\t"], ['&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;'], htmlspecialchars($line)) ?></p>
            <?php } ?>
        </div>
    </div>
</div>
    <div class="w-[70%] flex mb-10">
        <div class="w-[30%]"></div>
        <div class="bg-white p-8 w-[70%] shadow-lg">
        <h2 class="font-bold text-xs text-gray-500 uppercase tracking-wider">Request</h2>
        <div class="mt-3 grid grid-cols-1 gap-10">
            <div>
                <div class="text-lg font-semibold flex items-center gap-2">
                    <span class="text-blue-600"><?= $actual_link ?></span>
                    <div class="text-blue-600 border-indigo-500/50 px-1.5 py-0.5 rounded-sm bg-opacity-20 border text-xs font-medium uppercase tracking-wider">
                        <?= $_SERVER['REQUEST_METHOD'] ?>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="bg-gray-100 group py-2">
                        <div class="mask-fade-x">
                            <pre class="pl-4 overflow-x-scroll scrollbar-hidden-x pr-12 font-mono leading-relaxed text-sm font-normal"><?= generate_curl_command($actual_link) ?></pre>
                        </div>
                        <div class="absolute top-2 right-3">
                            <button type="button" class="w-6 h-6 rounded-full flex items-center justify-center text-xs ~bg-white text-indigo-500 hover:~text-indigo-600 transform transition-animation shadow-md hover:shadow-lg active:shadow-sm active:translate-y-px&quot; opacity-0 transform scale-80 transition-animation delay-100 group-hover:opacity-100 group-hover:scale-100 " title="Copy to clipboard">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="copy"
                                     class="svg-inline--fa fa-copy " role="img" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 512 512">
                                    <path fill="currentColor" d="M384 96L384 0h-112c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48H464c26.51 0 48-21.49 48-48V128h-95.1C398.4 128 384 113.6 384 96zM416 0v96h96L416 0zM192 352V128h-144c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48h192c26.51 0 48-21.49 48-48L288 416h-32C220.7 416 192 387.3 192 352z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<style>
    .mask-fade-r {
        -webkit-mask-image: linear-gradient(90deg, #000 0, #000 calc(100% - 4rem), transparent calc(100% - 2rem));
    }

    .scrollbar-lg::-webkit-scrollbar, .scrollbar-lg::-webkit-scrollbar-corner {
        width: 4px;
        height: 4px
    }

    .scrollbar-lg::-webkit-scrollbar-track {
        background-color: transparent
    }

    .scrollbar-lg::-webkit-scrollbar-thumb {
        background-color: rgb(96 165 250);
    }

    .scrollbar-hidden-x {
        -ms-overflow-style: none;
        scrollbar-width: none;
        overflow-x: scroll
    }

    .scrollbar-hidden-x::-webkit-scrollbar {
        display: none
    }

    .mask-fade-x {
        -webkit-mask-image: linear-gradient(90deg,transparent 0,#000 1rem,#000 calc(100% - 3rem),transparent calc(100% - 1rem));
    }
</style>
<script>
    let lastScrollPosition = 0;
    let nav = document.getElementById("nav");

    window.onscroll = function() {
        let currentScrollPosition = window.pageYOffset;
        if (currentScrollPosition > lastScrollPosition) {
            nav.classList.remove("bg-gray-300")
            nav.classList.add("bg-white", "border-b")
        } else if(currentScrollPosition < 50) {
            nav.classList.remove("bg-white", "border-b")
            nav.classList.add("bg-gray-300")
        }
        lastScrollPosition = currentScrollPosition;
    };
</script>
</html>