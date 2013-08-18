<?php
namespace Timurib\Bundle\MailTemplateBundle\Mail;

use Doctrine\Common\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;
use Timurib\Bundle\MailTemplateBundle\Entity\Template;
use Timurib\Bundle\MailTemplateBundle\Mail\Exception\UndefinedTemplateException;
use Timurib\Bundle\MailTemplateBundle\Mail\Exception\MissingVariableException;

/**
 * @TODO too many functions
 *
 * @author Timur Ibragimov <timok@ya.ru>
 */
class MessageManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param array $config
     * @param \Doctrine\Common\Persistence\ObjectRepository $repository
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        array $config,
        ObjectRepository $repository,
        \Twig_Environment $twig,
        \Swift_Mailer $mailer,
        LoggerInterface $logger
    ) {
        $this->config     = $config;
        $this->repository = $repository;
        $this->twig       = $twig;
        $this->mailer     = $mailer;
        $this->logger     = $logger;
    }

    /**
     * @param string $code
     * @param array $vars
     */
    public function send($code, array $vars = array())
    {
        $message = $this->create($code, $vars);
        if ($message !== null) {
            $this->logger->info(sprintf(
                'Send message by template "%s" (subject: "%s", from %s, to %s)',
                $code,
                $message->getSubject(),
                $message->getFrom(),
                $message->getTo()
            ));
            $this->mailer->send($message);
            return true;
        } else {
            $this->logger->info(sprintf('Message sending skipped (%s)', $code));
            return false;
        }
    }

    /**
     * @param string $code
     * @param array $vars
     * @return \Swift_Message
     * @throws UndefinedTemplateException
     * @throws MissingVariableException
     */
    public function create($code, array $vars = array())
    {
        if (! array_key_exists($code, $this->config['templates'])) {
            throw new UndefinedTemplateException($code);
        }

        $templateConfig = $this->config['templates'][$code];

        foreach ($templateConfig['vars'] as $expectedVar) {
            if (! array_key_exists($expectedVar, $vars)) {
                throw new MissingVariableException($expectedVar);
            }
        }

        /* @var $template \Timurib\Bundle\MailTemplateBundle\Entity\Template */
        $template = $this->find($code);

        if ($template === null) {
            return null;
        }

        return \Swift_Message::newInstance()
            ->setSubject($template->getSubject())
            ->setFrom($template->getFrom())
            ->setTo($template->getTo())
            ->setBody($this->render($template, $vars));
    }

    /**
     * @param \Timurib\Bundle\MailTemplateBundle\Entity\Template $template
     * @param array $vars
     * @return string
     */
    public function render(Template $template, array $vars)
    {
        return $this->twig->render($template->getBody(), $vars);
    }

    /**
     * @return array
     */
    public function map()
    {
        $map      = $this->config['templates'];
        $entities = $this->repository->findByCode(array_keys($map));

        foreach ($entities as $entity) {
            $code = $entity->getCode();
            if (array_key_exists($code, $map)) {
                $map[$code]['object'] = $entity;
            }
        }

        return $map;
    }

    /**
     * @param string $code
     * @return Template
     */
    public function find($code)
    {
        return $this->repository->findOneByCode($code);
    }
}
