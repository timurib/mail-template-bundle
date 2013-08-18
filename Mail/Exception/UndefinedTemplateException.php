<?php
namespace Timurib\Bundle\MailTemplateBundle\Mail\Exception;

/**
 * @author Timur Ibragimov <timok@ya.ru>
 */
class UndefinedTemplateException extends \RuntimeException
{
    private $templateCode;

    public function __construct($templateCode, \Exception $previous = null, $code = 0)
    {
        $this->templateCode = $templateCode;
        parent::__construct('Undefined template: '.$templateCode, $code, $previous);
    }

    public function getTemplateCode()
    {
        return $this->templateCode;
    }
}
