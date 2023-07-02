<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/contact')]
class ContactController extends AbstractController
{

    #[Route('/', name: 'app_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        $formattedRequest = $request->request->all();

        if($form->isSubmitted()){
            if(!empty($formattedRequest['contact']['name']) && !empty($formattedRequest['contact']['email']) && !empty($formattedRequest['contact']['message'])){
                if ($form->isValid()) {
                    $contactRepository->save($contact, true);

                    $this->addFlash('success', 'Köszönjük szépen a kérdésedet. Válaszunkkal hamarosan keresünk a megadott e-mail címen.');
        
                    return $this->redirectToRoute('app_contact_new', [], Response::HTTP_SEE_OTHER);
                }
            }else{
                $form->addError(new FormError('Hiba! Kérjük töltsd ki az
                összes mezőt!'));
            }
        }

        return $this->renderForm('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/list', name: 'app_contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $query = $contactRepository->findAll();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('contact/index.html.twig', [
            'contacts' => $pagination,
        ]);
    }

    #[Route('/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contact_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        $formattedRequest = $request->request->all();

        if ($form->isSubmitted() && $form->isValid()) {
            if(!empty($formattedRequest['contact']['name']) && !empty($formattedRequest['contact']['email']) && !empty($formattedRequest['contact']['message'])){
                $contactRepository->save($contact, true);

                return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $form->addError(new FormError('Hiba! Kérjük töltsd ki az
                összes mezőt!'));
            }
        }

        return $this->renderForm('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contact_delete', methods: ['POST'])]
    public function delete(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $contactRepository->remove($contact, true);
        }

        return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
