<?php

    namespace commands;

    use TgUserbot\Classes\Helper;
    use TgUserbot\Interfaces\CommandHandler;

    /**
     * Class ping
     * @package commands
     */
    class ping extends \TgUserbot\Abstracts\CommandHandler implements CommandHandler
    {
        /**
         * @return mixed|void
         */
        public function execute()
        {
            Helper::answerCommand($this->mainEventHandler, $this->update, "Pong!");
        }
    }