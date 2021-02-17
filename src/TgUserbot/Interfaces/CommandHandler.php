<?php


    namespace TgUserbot\Interfaces;

    /**
     * Interface UpdateHandler
     * @package TgUserbot\Interfaces
     */
    interface CommandHandler
    {
        /**
         * Main execution point for the command
         *
         * @return mixed
         */
        public function execute();
    }