<?php

namespace App\Controller;

use App\Entity\Support;
use App\Form\SupportType;
use App\Repository\SupportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SupportController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class SupportController extends AbstractController
{
    private $supportRepository;
    private $em;

    public function __construct(SupportRepository $supportRepository, EntityManagerInterface $em)
    {
        $this->supportRepository = $supportRepository;
        $this->em = $em;
    }

    /**
     * @Route("/support/list", name="support_list")
     */
    public function index()
    {
        $supports = $this->supportRepository->findAll();

        return $this->render('support/index.html.twig', [
            'supports' => $supports,
        ]);
    }

    /**
     * @Route("/support/new", name="support_new")
     * @param Request $request
     * @return Response
     */
    public function addSupport(Request $request): Response
    {
        $support = new Support();

        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($support);
            $this->em->flush();

            $this->addFlash('success', 'Support created.');
        }

        return $this->render('support/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/support/edit/{id}", name="support_edit")
     * @ParamConverter("support", options={"id"="id"})
     * @param Request $request
     * @param Support $support
     * @return Response
     */
    public function editEditor(Request $request, Support $support): Response
    {
        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($support);
            $this->em->flush();

            $this->addFlash('success', 'Support updated.');
        }

        return $this->render('support/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/support/delete/{id}", name="support_delete")
     * @ParamConverter("support", options={"id"="id"})
     * @param Support $support
     * @return Response
     */
    public function deleteEditor(Support $support): Response
    {
        $this->em->remove($support);
        $this->em->flush();

        $this->addFlash('success', 'Support deleted.');

        return $this->redirectToRoute('support_list');
    }
}
