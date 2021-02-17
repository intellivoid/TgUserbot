<?php


    namespace TgUserbot\Abstracts;

    use danog\MadelineProto\MTProto;
    use TgUserbot\MainEventHandler;

    /**
     * Class UpdateHandler
     * @package TgUserbot\Abstracts
     */
    abstract class CommandHandler implements \TgUserbot\Interfaces\CommandHandler
    {
        /**
         * The update data
         *
         * @var array
         */
        public array $update;

        /**
         * @var MainEventHandler
         */
        public MainEventHandler $mainEventHandler;

        /**
         * The API object
         *
         * @var MTProto
         */
        public MTProto $API;
    }