<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {

        $data = new ContactDTO();

        $data->name = '';
        $data->email = '';
        $data->message = '';
        $data->service = '';

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $email = (new TemplatedEmail())
                    ->from($data->email)
                    ->to($data->service)
                    ->subject('Test mail Cuisine App')
                    ->htmlTemplate('emails/contact.html.twig')                
                    ->context(['data' => $data]);

                    $mailer->send($email);
                    $this->addFlash('success', 'Message envoyé !');
                    
                    return $this->redirectToRoute('contact');    
            } catch (\Exception $e) {
                    $this->addFlash('danger', 'Impossible d\'envoyer le mail !');
                }                           
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
            // 'controller_name' => 'ContactController',
        ]);
    }
}
