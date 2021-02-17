<?php

    require("ppm");
    ppm_import("net.intellivoid.tguserbot");

    $Userbot = new \TgUserbot\TgUserbot("test");
    $configuration = __DIR__ . DIRECTORY_SEPARATOR . "configuration.json";
    $working_directory = __DIR__;
    if(file_exists($configuration) == false)
    {
        $configuration = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "configuration.json";
        $working_directory = __DIR__ . DIRECTORY_SEPARATOR . "..";
    }
    $Userbot->loadConfiguration($configuration, $working_directory);
    $Userbot->start();