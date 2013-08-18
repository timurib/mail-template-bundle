<?php
namespace Timurib\Bundle\MailTemplateBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Timur Ibragimov <timok@ya.ru>
 */
class TwigSyntaxValidator extends ConstraintValidator
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function validate($value, Constraint $constraint)
    {
        try {
            // @TODO check unexpected variables
            $this->twig->parse($this->twig->tokenize($value));
        } catch (\Twig_Error_Syntax $e) {
            $this->context->addViolation($constraint->message, array(
                '%error%' => $e->getMessage()
            ));
        }
    }
}
