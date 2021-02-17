<?php

    namespace commands;

    use TgUserbot\Classes\Helper;
    use TgUserbot\Interfaces\CommandHandler;

    /**
     * Class ping
     * @package commands
     */
    class generic_handler extends \TgUserbot\Abstracts\CommandHandler implements CommandHandler
    {
        public static $UserCache = [];

        public static $UsersResolveQueue = [];

        public static $NextResolveTime = null;

        /**
         * @return mixed|void
         */
        public function execute()
        {
            if(isset($this->update["message"]["from_id"]) == false) return;
            if($this->update["message"]["from_id"]["_"] == "peerUser")
            {
                if(isset(self::$UserCache[$this->update["message"]["from_id"]["user_id"]]))
                {
                    $user_cache = self::$UserCache[$this->update["message"]["from_id"]["user_id"]];
                    if($user_cache["cache"] < time())
                    {
                        return;
                    }
                }

                self::$UserCache[$this->update["message"]["from_id"]["user_id"]] = [
                    "cache" => time() + 3600
                ];

                if(in_array($this->update["message"]["from_id"]["user_id"], self::$UsersResolveQueue) == false)
                {
                    self::$UsersResolveQueue[] = $this->update["message"]["from_id"]["user_id"];
                    var_dump("Added user to queue, " . count(self::$UsersResolveQueue) . " users to resolve");
                }
            }

            if(self::$NextResolveTime == null)
            {
                self::$NextResolveTime = time() + 5;
            }

            if(time() > self::$NextResolveTime)
            {
                var_dump($this->mainEventHandler->users->getUsers(["id" => self::$UsersResolveQueue]));
                self::$UsersResolveQueue = [];
                self::$NextResolveTime = time() + 5;

            }
        }
    }