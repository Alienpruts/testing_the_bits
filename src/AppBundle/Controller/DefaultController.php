<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Enclosure;
use AppBundle\Factory\DinosaurFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $enclosures = $this->getDoctrine()->getRepository(Enclosure::class)->findAll();


        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'enclosures' => $enclosures,
        ]);
    }

    /**
     * @Route("/grow", name="grow_dinosaur")
     * @Method({"POST"})
     * @param Request $request
     * @param DinosaurFactory $dinosaurFactory
     */
    public function growAction(Request $request, DinosaurFactory $dinosaurFactory)
    {
        $manager = $this->getDoctrine()->getManager();

        /** @var Enclosure $enclosure */
        $enclosure = $manager->getRepository(Enclosure::class)
            ->find($request->request->get('enclosure'));

        $spec = $request->request->get('specification');
        $dinosaur = $dinosaurFactory->growFromSpecification($spec);

        $dinosaur->setEnclosure($enclosure);
        $enclosure->addDinosaur($dinosaur);

        $manager->flush();

        $this->addFlash('success', sprintf('Grew a %s in enclosure #%d', mb_strtolower($spec), $enclosure->getId()));

        return $this->redirectToRoute('homepage');

    }
}
