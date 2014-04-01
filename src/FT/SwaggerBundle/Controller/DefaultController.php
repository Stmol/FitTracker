<?php

namespace FT\SwaggerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package FT\SwaggerBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('FTSwaggerBundle:Default:index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function apidocsAction()
    {
        return $this->render('FTSwaggerBundle:Default:apidocs.json.twig');
    }

    /**
     * @param $apiName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function apiAction($apiName)
    {
        return $this->render("FTSwaggerBundle:Api:{$apiName}.json.twig");
    }
}
