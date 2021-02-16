<?php

    namespace TgUserbot;

    use danog\MadelineProto\API;
    use danog\MadelineProto\EventHandler;
    use danog\MadelineProto\Exception;
    use danog\MadelineProto\Logger;
    use danog\MadelineProto\MTProto;
    use danog\MadelineProto\Settings;
    use function json_encode;

    require("ppm");
    ppm_import("com.danog.madelineproto");
    ppm_import("net.intellivoid.coffeehouse");

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

        /**
         * Handle updates from users.
         *
         * @param array $update Update
         *
         */
        public function onUpdateNewMessage(array $update)
        {
            if ($update['message']['_'] === 'messageEmpty') {
                return;
            }

            if(
                $update["message"]["_"] == "message" &&
                $update["message"]["out"] == true
            )
            {
                switch($update["message"]["message"])
                {
                    case ".ping":
                        $this->messages->editMessage([
                            "id" => $update["message"]["id"],
                            "peer" => $update["message"]["to_id"],
                            "message" => "Pong!"
                        ]);
                        break;

                    case ".debug":
                        $this->messages->editMessage([
                            "id" => $update["message"]["id"],
                            "peer" => $update["message"]["to_id"],
                            "message" => json_encode(ppm_definitions(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                        ]);
                        break;
                }
            }


            $res = json_encode($update, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            var_dump($res);
        }

    }