<?php
namespace Timurib\Bundle\MailTemplateBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Timur Ibragimov <timok@ya.ru>
 *
 * @Annotation
 */
class TwigSyntax extends Constraint
{
    public $message = 'Twig syntax error: %error%';

    public function validatedBy()
    {
        return 'twig_syntax_validator';
    }
}
