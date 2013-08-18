<?php
namespace Timurib\Bundle\MailTemplateBundle\Mail\Exception;

/**
 * @author Timur Ibragimov <timok@ya.ru>
 */
class MissingVariableException extends \RuntimeException
{
    private $var;

    public function __construct($var, \Exception $previous = null, $code = 0)
    {
        $this->var = $var;
        parent::__construct('Missing variable: '.$var, $code, $previous);
    }

    public function getVar()
    {
        return $this->var;
    }
}
