<?php

require("ppm");
ppm_import("net.intellivoid.tguserbot");

$Userbot = new \TgUserbot\TgUserbot("test");
$Userbot->loadConfiguration(__DIR__ . DIRECTORY_SEPARATOR . "configuration.json", __DIR__);
$Userbot->start();