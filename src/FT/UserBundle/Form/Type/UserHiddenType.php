<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 09.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\UserBundle\Form\Type;

use FT\UserBundle\Form\DataTransformer\UserToIdTransformer;
use FT\UserBundle\Manager\UserManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserHiddenType
 * @package FT\UserBundle\Form\Type
 * @author Yury Smidovich <dev@stmol.me>
 */
class UserHiddenType extends AbstractType
{
    /**
     * @var \FT\UserBundle\Manager\UserManager
     */
    private $userManager;

    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new UserToIdTransformer($this->userManager);
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'User does not exist',
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'user_hidden';
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'hidden';
    }
}
