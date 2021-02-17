<?php


    namespace TgUserbot\Exceptions;


    use Exception;
    use Throwable;

    /**
     * Class CommandNotFoundException
     * @package TgUserbot\Exceptions
     */
    class CommandNotFoundException extends Exception
    {
        /**
         * CommandNotFoundException constructor.
         * @param string $message
         * @param int $code
         * @param Throwable|null $previous
         */
        public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }