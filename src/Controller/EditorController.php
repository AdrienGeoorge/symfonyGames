<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class EditorController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/editor/list", name="editor_list")
     * @param EditorRepository $editorRepository
     * @return Response
     */
    public function index(EditorRepository $editorRepository): Response
    {
        $editors = $editorRepository->findAll();

        return $this->render('editor/index.html.twig', [
            'editors' => $editors,
        ]);
    }

    /**
     * @Route("/editor/infos/{id}", name="editor_infos")
     * @ParamConverter("editor", options={"id"="id"})
     * @param Editor $editor
     * @return Response
     */
    public function getInfos(Editor $editor): Response
    {
        return $this->render('editor/infos.html.twig', [
            'editor' => $editor
        ]);
    }

    /**
     * @Route("/editor/new", name="editor_new")
     * @param Request $request
     * @return Response
     */
    public function addEditor(Request $request): Response
    {
        $editor = new Editor();

        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($editor);
            $this->em->flush();

            $this->addFlash('success', 'Editor created.');
        }

        return $this->render('editor/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editor/edit/{id}", name="editor_edit")
     * @ParamConverter("editor", options={"id"="id"})
     * @param Request $request
     * @param Editor $editor
     * @return Response
     */
    public function editEditor(Request $request, Editor $editor): Response
    {
        $form = $this->createForm(EditorType::class, $editor);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($editor);
            $this->em->flush();

            $this->addFlash('success', 'Editor updated.');
        }

        return $this->render('editor/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editor/delete/{id}", name="editor_delete")
     * @ParamConverter("editor", options={"id"="id"})
     * @param Editor $editor
     * @return Response
     */
    public function deleteEditor(Editor $editor): Response
    {
        $this->em->remove($editor);
        $this->em->flush();

        $this->addFlash('success', 'Editor deleted.');

        return $this->redirectToRoute('editor_list');
    }
}
