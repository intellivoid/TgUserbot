<?php

    namespace TgUserbot;

    use danog\MadelineProto\EventHandler;
    use TgUserbot\Exceptions\CommandNotFoundException;
    use function json_encode;

    require("ppm");
    ppm_import("com.danog.madelineproto");

    /**
     * Event handler class.
     */
    class MainEventHandler extends EventHandler
    {
        /**
         * Handle updates from supergroups and channels.
         *
         * @param array $update Update
         *
         * @return void
         */
        public function onUpdateNewChannelMessage(array $update)
        {
            $this->onUpdateNewMessage($update);
        }

        private function commandHandler(array $update)
        {
            if(TgUserbot::$UserbotConfiguration == null) return;

            // Verify the message contents
            if(isset($update["message"]) == false || $update["message"]["_"] !== "message") return;
            if(strlen($update["message"]["message"]) == 0 || strlen($update["message"]["message"]) == null) return;

            if(substr($update["message"]["message"], 0, 1) == TgUserbot::$UserbotConfiguration->CommandConfiguration->Prefix)
            {
                $CommandInput = explode(" ", $update["message"]["message"], 1)[0];

                try
                {
                    $Command = TgUserbot::$UserbotConfiguration->CommandConfiguration->findCommand($CommandInput);
                }
                catch(CommandNotFoundException $e)
                {
                    return;
                }

                if($Command->AllowRemoteExecution == false)
                {
                    if($update["message"]["out"] == false)
                    {
                        return;
                    }

                    $Command->execute($update, $this, $this->API);
                }
                else
                {
                    if($update["message"]["from_id"]["_"] == "peerUser")
                    {
                        $Command->execute($update, $this, $this->API);;
                    }
                }
            }
        }

        /**
         * Handle updates from users.
         *
         * @param array $update Update
         *
         */
        public function onUpdateNewMessage(array $update)
        {
            $this->messages;
            if ($update['message']['_'] === 'messageEmpty') {
                return;
            }

            $this->commandHandler($update);

            //$res = json_encode($update, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            //var_dump($res);
        }

    }