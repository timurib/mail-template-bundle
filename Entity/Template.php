<?php
namespace Timurib\Bundle\MailTemplateBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Timurib\Bundle\MailTemplateBundle\Validator\Constraints\TwigSyntax;

/**
 * Mail template
 *
 * @author Timur Ibragimov <timok@ya.ru>
 *
 * @UniqueEntity("code")
 */
class Template
{
    /**
     * Unique entity code
     *
     * @var string
     */
    protected $code;

    /**
     * Message's sender
     *
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=255)
     */
    protected $from;

    /**
     * Message's recipient
     *
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Length(max=255)
     */
    protected $to;

    /**
     * A brief summary of the topic of the message
     *
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    protected $subject;

    /**
     * Content of template
     *
     * @var string
     *
     * @Assert\NotBlank
     * @TwigSyntax
     */
    protected $body;

    public function __toString()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return \Timurib\Bundle\MailTemplateBundle\Entity\Template
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     * @return \Timurib\Bundle\MailTemplateBundle\Entity\Template
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     * @return \Timurib\Bundle\MailTemplateBundle\Entity\Template
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return \Timurib\Bundle\MailTemplateBundle\Entity\Template
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return \Timurib\Bundle\MailTemplateBundle\Entity\Template
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

}
