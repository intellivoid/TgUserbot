<?php


    namespace TgUserbot\Objects\UserbotConfiguration;

    use TgUserbot\Exceptions\CommandNotFoundException;

    /**
     * Class CommandConfiguration
     * @package TgUserbot\Objects\UserbotConfiguration
     */
    class CommandConfiguration
    {
        /**
         * @var
         */
        public $Prefix;

        /**
         * @var
         */
        public $GenericHandler;

        /**
         * @var Command[]
         */
        public $Commands;

        /**
         * Finds a command from the Userbot Configuration
         *
         * @param string $command
         * @return Command
         * @throws CommandNotFoundException
         */
        public function findCommand(string $command): Command
        {
            $command = str_ireplace($this->Prefix, "", strtolower($command));

            foreach($this->Commands as $command_object)
            {
                if(strtolower($command_object->Name) == $command)
                    return $command_object;
            }

            throw new CommandNotFoundException("The command $command is not registered");
        }

        /**
         * @param array $data
         * @param string|null $working_directory
         * @return CommandConfiguration
         */
        public static function fromArray(array $data, string $working_directory=null): CommandConfiguration
        {
            $CommandConfiguration = new CommandConfiguration();

            if(isset($data["prefix"]))
                $CommandConfiguration->Prefix = $data["prefix"];

            if(isset($data["generic_handler"]))
            {
                if($working_directory == null)
                {
                    $CommandConfiguration->GenericHandler = $data["generic_handler"];
                }
                else
                {
                    $CommandConfiguration->GenericHandler = $working_directory . DIRECTORY_SEPARATOR . $data["generic_handler"];
                }
            }

            if(isset($data["commands"]))
            {
                $CommandConfiguration->Commands = [];

                foreach($data["commands"] as $command)
                {
                    $CommandConfiguration->Commands[] = Command::fromArray($command, $working_directory);
                }
            }

            return $CommandConfiguration;
        }
    }