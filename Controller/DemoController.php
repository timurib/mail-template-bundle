<?php
namespace Timurib\Bundle\MailTemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @author Timur Ibragimov <timok@ya.ru>
 */
class DemoController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {
        return array(
            'map' => $this->getMessageManager()->map()
        );
    }

    /**
     * @Route("/send/{code}")
     * @Template
     */
    public function sendAction($code)
    {
        $map = $this->getMessageManager()->map();

        if (! array_key_exists($code, $map)) {
            throw $this->createNotFoundException();
        }

        $form    = $this->createFormForVars($map[$code]['vars']);
        $request = $this->getRequest();
        $message = 'Set up values';

        if ($request->isMethod('POST')) {
            $values = $request->request->get('form');
            if ($this->getMessageManager()->send($code, $values)) {
                $message = 'Message sent';
            } else {
                $message = 'Message was not sent';
            }
        }

        return array(
            'message' => $message,
            'code'    => $code,
            'form'    => $form->createView()
        );
    }

    /**
     * @return \Timurib\Bundle\MailTemplateBundle\Mail\MessageManager
     */
    protected function getMessageManager()
    {
        return $this->get('timurib.mail_template.manager');
    }

    /**
     * @param array $vars
     * @return \Symfony\Component\Form\Form
     */
    protected function createFormForVars(array $vars)
    {
        $builder = $this->createFormBuilder();

        foreach ($vars as $var) {
            $builder->add($var, 'text', array('label' => $var));
        }

        return $builder->getForm();
    }
}
