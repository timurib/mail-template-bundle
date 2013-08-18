<?php
namespace Timurib\Bundle\MailTemplateBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Timurib\Bundle\MailTemplateBundle\Form\Type\TemplateType;

/**
 * Backend actions controller
 *
 * @author Timur Ibragimov <timok@ya.ru>
 */
class AdminController extends CRUDController
{
    /**
     * Show list of all templates defined by configuration
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }
        return $this->render($this->admin->getTemplate('list'), array(
            'map' => $this->getMessageManager()->map()
        ));
    }

    /**
     * Set up template attributes and store (create or update) it in ORM
     *
     * @TODO: method too big, need refactor
     *
     * @param string $code
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function enableAction($code = null)
    {
        $config = $this->getConfig();
        if (! array_key_exists($code, $config['templates'])) {
            throw new NotFoundHttpException(sprintf('unable to find template with code: %s', $code));
        }

        $object = $this->getMessageManager()->find($code);

        if ($object === null) {
            $existent = false;
            $object = $this->admin->getNewInstance();
            $object->setCode($code);
            $object->setSubject($config['templates'][$code]['label']);
        } else {
            $existent = true;
        }

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        $request = $this->getRequest();
        $form    = $this->createForm(new TemplateType(), $object, array(
            'translation_domain' => 'TimuribMailTemplateBundle'
        ));

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                if ($existent) {
                    $this->admin->update($object);
                } else {
                    $this->admin->create($object);
                }
                $this->addFlash('sonata_flash_success', 'flash_edit_success');
                return $this->redirect($this->admin->generateUrl('list'));
            }
        }

        $theme = $this->admin->getFormTheme();
        $view  = $form->createView();

        $this->get('twig')
            ->getExtension('form')
            ->renderer
            ->setTheme($view, $theme);

        return $this->render($this->admin->getTemplate('enable'), array(
            'vars'   => $config['templates'][$code]['vars'],
            'action' => 'enable',
            'form'   => $view,
            'object' => $object,
        ));
    }

    /**
     * Disable template (delete correspond entity from ORM)
     *
     * @param string $code
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function disableAction($code = null)
    {
        $config = $this->getConfig();

        if (! array_key_exists($code, $config['templates'])) {
            throw new NotFoundHttpException(sprintf('template with code %s is not configured', $code));
        }

        $object = $this->getRepository()->findOneByCode($code);

        if (! $object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $code));
        }

        if (false === $this->admin->isGranted('DELETE', $object)) {
            throw new AccessDeniedException();
        }

        $form    = $this->createFormBuilder()->getForm();
        $request = $this->getRequest();

        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $this->admin->delete($object);
                $this->addFlash('sonata_flash_success', 'flash_delete_success');
                return $this->redirect($this->admin->generateUrl('list'));
            }
        }

        return $this->render($this->admin->getTemplate('disable'), array(
            'object' => $object,
            'action' => 'disable',
            'form'   => $form->createView(),
        ));
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return $this->container->getParameter('timurib_mail_template');
    }

    /**
     * @return \Timurib\Bundle\MailTemplateBundle\Mail\MessageManager
     */
    protected function getMessageManager()
    {
        return $this->get('timurib.mail_template.manager');
    }
}
