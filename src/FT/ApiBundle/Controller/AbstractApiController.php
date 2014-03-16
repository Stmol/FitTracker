<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 28.02.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;

abstract class AbstractApiController extends Controller
{
    /**
     * @return \JMS\Serializer\Serializer
     */
    protected function getSerializer()
    {
        return $this->get('jms_serializer');
    }

    /**
     * Parse form errors
     *
     * @param  FormInterface $form
     * @return array
     */
    protected function getFormErrors(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $child) {
            if ($err = $this->getFormErrors($child)) {
                $errors[$key] = $err;
            }
        }

        return $errors;
    }

    /**
     * Get manager for entity
     *
     * @return \FT\AppBundle\Manager\EntityManagerInterface
     */
    abstract protected function getEntityManager();
}
