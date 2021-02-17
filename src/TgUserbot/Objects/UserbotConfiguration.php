<?php


    namespace TgUserbot\Objects;


    use TgUserbot\Objects\UserbotConfiguration\CommandConfiguration;

    /**
     * Class UserbotConfiguration
     * @package TgUserbot\Objects
     */
    class UserbotConfiguration
    {
        /**
         * @var CommandConfiguration
         */
        public $CommandConfiguration;

        /**
         * @param array $data
         * @param string|null $working_directory
         * @return UserbotConfiguration
         */
        public static function fromArray(array $data, string $working_directory=null): UserbotConfiguration
        {
            $UserbotConfigurationObject = new UserbotConfiguration();

            if(isset($data["command_configuration"]))
                $UserbotConfigurationObject->CommandConfiguration = CommandConfiguration::fromArray($data["command_configuration"], $working_directory);

            return $UserbotConfigurationObject;
        }
    }