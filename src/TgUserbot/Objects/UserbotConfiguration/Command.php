<?php


    namespace TgUserbot\Objects\UserbotConfiguration;

    use danog\MadelineProto\API;
    use danog\MadelineProto\APIFactory;
    use danog\MadelineProto\messages;
    use danog\MadelineProto\MTProto;
    use TgUserbot\Abstracts\CommandHandler;
    use TgUserbot\MainEventHandler;

    /**
     * Class Command
     * @package TgUserbot\Objects\UserbotConfiguration
     */
    class Command
    {
        /**
         * The name of the command
         *
         * @var string
         */
        public $Name;

        /**
         * The class namespace
         *
         * @var string
         */
        public $Namespace;

        /**
         * The class namespace
         *
         * @var string
         */
        public $ClassName;

        /**
         * Indicates if other users other than the host can execute this command
         *
         * @var bool
         */
        public $AllowRemoteExecution;

        /**
         * The file path to be executed when the command is called
         *
         * @var string
         */
        public $ExecutionFile;

        /**
         * Returns an array representation of this object
         *
         * @return array
         */
        public function toArray(): array
        {
            return [
                "name" => $this->Name,
                "namespace" => $this->Namespace,
                "class_name" => $this->ClassName,
                "remote_execution" => $this->AllowRemoteExecution,
                "file" => $this->ExecutionFile
            ];
        }

        /**
         * Constructs the object from an array
         *
         * @param array $data
         * @param string|null $working_directory
         * @return Command
         */
        public static function fromArray(array $data, string $working_directory=null): Command
        {
            $CommandObject = new Command();

            if(isset($data["name"]))
            {
                $CommandObject->Name = $data["name"];
            }

            if(isset($data["namespace"]))
            {
                $CommandObject->Namespace = $data["namespace"];
            }

            if(isset($data["class_name"]))
            {
                $CommandObject->ClassName = $data["class_name"];
            }

            if(isset($data["remote_execution"]))
            {
                $CommandObject->AllowRemoteExecution = $data["remote_execution"];
            }

            if(isset($data["file"]))
            {
                if($working_directory == null)
                {
                    $CommandObject->ExecutionFile = $data["file"];
                }
                else
                {
                    $CommandObject->ExecutionFile = $working_directory . DIRECTORY_SEPARATOR . $data["file"];
                }
            }

            return $CommandObject;
        }

        /**
         * Executes the command
         *
         * @param array $update
         * @param MainEventHandler $mainEventHandler
         * @param MTProto $api
         */
        public function execute(array $update, MainEventHandler $mainEventHandler, MTProto $api)
        {
            /** @noinspection PhpIncludeInspection */
            require_once($this->ExecutionFile);

            $command_namespace = $this->Namespace . "\\" . $this->ClassName;

            /** @var CommandHandler $command_object */
            $command_object = new $command_namespace;
            $command_object->update = $update;
            $command_object->mainEventHandler = $mainEventHandler;
            $command_object->API = $api;

            $command_object->execute();
        }
    }