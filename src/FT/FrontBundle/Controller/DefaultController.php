<?php
/**
 * Created by Yury Smidovich (Stmol)
 * Date: 06.04.14
 * Project: Fittracker
 * Url: http://stmol.me
 * Email: dev@stmol.me
 */

namespace FT\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package FT\FrontBundle\Controller
 * @author Yury Smidovich <dev@stmol.me>
 */
class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('FTFrontBundle:Default:index.html.twig');
    }
}
