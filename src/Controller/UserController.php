<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/settings", name="settings")
 */
class UserController extends AbstractController
{

	private $em;
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	/**
	 * @Route("/user/list", name="list_user")
	 * @param UserRepository $userRepository
	 *
	 * @return Response
	 */
	public function index(UserRepository $userRepository):Response
	{
		$userRepository = $userRepository->findAll();

		return $this->render('user/infos.html.twig', [
			'users' => $userRepository,
		]);
	}
	/**
	 * @Route("/user/edit/{id}", name="user_edit")
	 * @ParamConverter("user", options={"id"="id"})
	 * @param Request $request
	 * @param User $User
	 * @return Response
	 */
	public function editEditor(Request $request, User $User): Response
	{
		$form = $this->createForm(UserEditType::class, $User);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$this->em->persist($User);
			$this->em->flush();

			$this->addFlash('success', 'User updated.');
		}
		return $this->render('user/form.html.twig', [
			'form' => $form->createView()
		]);
	}
	/**
	 * @Route("/user/delete/{id}", name="user_delete")
	 * @ParamConverter("editor", options={"id"="id"})
	 * @param User $editor
	 * @return Response
	 */
	public function deleteEditor(User $editor): Response
	{
		$this->em->remove($editor);
		$this->em->flush();
		$this->addFlash('success', 'User deleted.');

		return $this->redirectToRoute('user_list');
	}
}
