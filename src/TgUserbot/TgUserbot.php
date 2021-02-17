<?php

    /** @noinspection PhpPrivateFieldCanBeLocalVariableInspection */

    namespace TgUserbot;

    use acm\acm;
    use acm\Objects\Schema;
    use danog\MadelineProto\API;
    use danog\MadelineProto\Logger;
    use danog\MadelineProto\MTProto;
    use danog\MadelineProto\Settings;
    use TgUserbot\Objects\UserbotConfiguration;
    use TgUserbot\Objects\UserbotConfiguration\Command;

    /**
     * Class TgUserbot
     * @package TgUserbot
     */
    class TgUserbot
    {
        /**
         * @var string
         */
        private string $name;

        /**
         * @var acm
         */
        private acm $acm;

        /**
         * @var Settings
         */
        private $client_settings;
        /**
         * @var Settings\AppInfo
         */
        private $app_info;

        /**
         * @var mixed
         */
        private $app_config;

        /**
         * @var API|null
         */
        private $madeline_proto;

        /**
         * @var mixed
         */
        private $client_config;

        /**
         * Authorization sceptical
         *
         * @var mixed
         */
        private $authorization;

        /**
         * @var string
         */
        private string $session_path;

        /**
         * @var UserbotConfiguration|null
         */
        public static $UserbotConfiguration;

        /**
         * TgUserbot constructor.
         * @param string $name
         * @throws \Exception
         */
        public function __construct(string $name)
        {
            // Define configuration
            $this->name = $name . "_userbot";
            $this->acm = new acm(__DIR__, $this->name);
            $this->madeline_proto = null;
            $this->autoConfig();

            $this->app_config = $this->acm->getConfiguration('Application');
            $this->client_config = $this->acm->getConfiguration('Client');

            // Define settings
            $this->client_settings = new Settings;
            $this->client_settings->getLogger()->setLevel(Logger::LOGGER_DEFAULT);

            // Define Application Settings
            $this->app_info = new Settings\AppInfo();
            $this->app_info->setApiId((int)$this->app_config["app_id"]);
            $this->app_info->setApiHash($this->app_config["app_hash"]);
            $this->app_info->setDeviceModel("Intellivoid Userbot");
            $this->app_info->setSystemVersion("PPM " . PPM_VERSION);
            $this->client_settings->setAppInfo($this->app_info);


            if(file_exists($this->client_config["session_directory"]) == false)
                mkdir($this->client_config["session_directory"]);

            $this->session_path = $this->client_config["session_directory"] .DIRECTORY_SEPARATOR . $this->name;
        }

        /**
         * @param string $path
         * @param string $directory
         */
        public function loadConfiguration(string $path, string $directory)
        {
            self::$UserbotConfiguration = UserbotConfiguration::fromArray(json_decode(file_get_contents($path), true), $directory);
        }

        /**
         * @return UserbotConfiguration|null
         */
        public function getUserbotConfiguration(): ?UserbotConfiguration
        {
            return self::$UserbotConfiguration;
        }

        /**
         * Initializes the MTProto Client
         */
        public function initializeClient()
        {
            if($this->madeline_proto == null)
            {
                $this->madeline_proto = new API($this->session_path, $this->client_settings);
            }
        }

        /**
         * Authenticates the user, skips the step if already authenticated
         *
         * @param string|null $code
         */
        public function authenticate(string $code=null)
        {
            $this->initializeClient();

            if($this->madeline_proto->getAuthorization() !== MTProto::LOGGED_IN)
            {
                $this->madeline_proto->phoneLogin($this->client_config["phone_number"]);
                if($code == null)
                {
                    $this->authorization = $this->madeline_proto->completePhoneLogin(readline("Verification Code: "));
                }
                else
                {
                    $this->authorization = $this->madeline_proto->completePhoneLogin($code);

                }

                if ($this->authorization['_'] === 'account.password')
                {
                    $this->authorization = $this->madeline_proto->complete2falogin($this->client_config["2fa_password"]);
                }
            }
        }

        /**
         * Starts the bot
         */
        public function start()
        {
            $this->authenticate();
            $this->madeline_proto->startAndLoop(MainEventHandler::class);
        }


        /**
         * AutoConfigures ACM
         */
        private function autoConfig()
        {
            $ApplicationSchema = new Schema();
            $ApplicationSchema->setDefinition('app_id', '<APP ID>');
            $ApplicationSchema->setDefinition('app_hash', '<APP HASH>');
            $this->acm->defineSchema('Application', $ApplicationSchema);

            $ApplicationSchema = new Schema();
            $ApplicationSchema->setDefinition('phone_number', '<PHONE NUMBER>');
            $ApplicationSchema->setDefinition('2fa_password', '<OPTIONAL 2FA PASSWORD>');
            $ApplicationSchema->setDefinition('session_directory', DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "userbot_sessions");
            $this->acm->defineSchema('Client', $ApplicationSchema);
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }

    }