<?php
namespace Timurib\Bundle\MailTemplateBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Sonata admin configuration
 *
 * @author Timur Ibragimov <timok@ya.ru>
 */
class TemplateAdmin extends Admin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
        $collection->add('enable', 'enable/{code}', array(), array('code' => '^[a-z0-9_]+$'));
        $collection->add('disable', 'disable/{code}', array(), array('code' => '^[a-z0-9_]+$'));
    }
}
