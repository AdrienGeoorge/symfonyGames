<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
	private $em;
	private $passwordEncoder;

	public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->em = $em;
		$this->passwordEncoder = $passwordEncoder;
	}

	/**
	 * @Route("/settings/edit", name="user_edit")
	 * @param Request $request
	 * @return Response
	 */
	public function editUser(Request $request): Response
	{
		$User = $this->getUser();
		$form = $this->createForm(UserEditType::class, $User);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->persist($User);
			$this->em->flush();
			$this->addFlash('success', 'User updated.');
		}
		return $this->render('user/edit.html.twig', [
			'form' => $form->createView()
		]);
	}

}
