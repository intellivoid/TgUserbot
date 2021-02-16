<?php

    /** @noinspection PhpPrivateFieldCanBeLocalVariableInspection */

    namespace TgUserbot;

    use acm\acm;
    use acm\Objects\Schema;
    use danog\MadelineProto\API;
    use danog\MadelineProto\Logger;
    use danog\MadelineProto\MTProto;
    use danog\MadelineProto\Settings;

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
        private Settings $settings;
        /**
         * @var Settings\AppInfo
         */
        private Settings\AppInfo $app_info;

        /**
         * @var mixed
         */
        private $app_config;

        /**
         * @var API|null
         */
        private ?API $madeline_proto;

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
            $this->settings = new Settings;
            $this->settings->getLogger()->setLevel(Logger::LOGGER_DEFAULT);

            // Define Application Settings
            $this->app_info = new Settings\AppInfo();
            $this->app_info->setApiId($this->app_config["app_id"]);
            $this->app_info->setApiHash($this->app_config["app_hash"]);
            $this->app_info->setDeviceModel("Intellivoid Userbot");
            $this->app_info->setSystemVersion("PPM " . PPM_VERSION);
            $this->settings->setAppInfo($this->app_info);
        }

        /**
         * Initializes the MTProto Client
         */
        public function initializeClient()
        {
            if($this->madeline_proto == null)
            {
                $this->madeline_proto = new API($this->client_config["session_directory"] .DIRECTORY_SEPARATOR . $this->name, $this->settings);
                $this->madeline_proto->stop();
            }
        }

        /**
         * Authenticates the user, skips the step if already authenticated
         *
         * @param string|null $code
         */
        public function authenticate(string $code=null)
        {
            if($this->madeline_proto->getAuthorization() !== MTProto::LOGGED_IN)
            {
                $this->madeline_proto->phoneLogin(readline($this->client_config["phone_number"]));
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
                else
                {
                    print("Cannot login, this account isn't supported, " . $this->authorization["_"] . PHP_EOL);
                    exit(1);
                }
            }
        }

        public function start()
        {
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