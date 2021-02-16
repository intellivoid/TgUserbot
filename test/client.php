<?php

    require("ppm");
    ppm_import("net.intellivoid.tguserbot");

    $Userbot = new \TgUserbot\TgUserbot("test");
    $Userbot->start();