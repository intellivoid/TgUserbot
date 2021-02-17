<?php


    namespace TgUserbot\Classes;

    use danog\MadelineProto\API;
    use TgUserbot\MainEventHandler;
    use TgUserbot\TgUserbot;

    /**
     * Class Helper
     * @package TgUserbot\Classes
     */
    class Helper
    {
        /**
         * Responds to a message command
         *
         * @param MainEventHandler $mainEventHandler
         * @param array $update
         * @param string $message
         * @return bool
         */
        public static function answerCommand(MainEventHandler $mainEventHandler, array $update, string $message): bool
        {
            if(TgUserbot::getSelfUser()["id"] !== $update["message"]["from_id"]["user_id"])
            {
                if($update["message"]["peer_id"]["_"] == "peerChat")
                {
                    $mainEventHandler->messages->sendMessage([
                        "peer" => $update["message"]["peer_id"],
                        "reply_to_msg_id" => $update["message"]["id"],
                        "message" => $message
                    ]);
                }
                else
                {
                    $mainEventHandler->messages->sendMessage([
                        "peer" => $update["message"]["from_id"],
                        "reply_to_msg_id" => $update["message"]["id"],
                        "message" => $message
                    ]);
                }


                return true;
            }
            elseif($update["message"]["out"] = true)
            {
                $mainEventHandler->messages->editMessage([
                    "id" => $update["message"]["id"],
                    "peer" => $update["message"]["peer_id"],
                    "message" => $message
                ]);

                return true;
            }

            return false;
        }
    }