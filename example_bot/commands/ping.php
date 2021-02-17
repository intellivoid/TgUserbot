<?php

    namespace commands;

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
            $this->mainEventHandler->messages->editMessage([
                "id" => $this->update["message"]["id"],
                "peer" => $this->update["message"]["peer_id"],
                "message" => "Pong!"
            ]);
        }
    }