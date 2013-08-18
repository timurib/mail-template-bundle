<?php
namespace Timurib\Bundle\MailTemplateBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Timur Ibragimov <timok@ya.ru>
 */
class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', 'email', array(
                'label' => 'form.from',
            ))
            ->add('to', 'email', array(
                'label' => 'form.to',
            ))
            ->add('subject', 'text', array(
                'label' => 'form.subject',
            ))
            ->add('body', 'textarea', array(
                'label' => 'form.body',
                'attr'  => array(
                    'class' => 'input-xxlarge',
                    'style' => 'height:150px'
                )
            ));
    }

    public function getName()
    {
        return 'template';
    }
}
