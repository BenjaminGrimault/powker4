<?php

namespace Powker4Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Powker4Bundle\Entity\Contact;
use Powker4Bundle\Entity\Address;
use Powker4Bundle\Form\ContactType;

class Powker4Controller extends Controller
{
    public function indexAction()
    {
        return $this->render('Powker4Bundle:Powker4:index.html.twig');
    }

    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formBuilder = new ContactType();
        $contact = new Contact();
        $address = new Address();
        $contact->addAddress($address);

        $form = $this->createForm($formBuilder, $contact, [
            'method' => 'POST',
            'action' => $this->generateUrl('powker4_contact'),
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $transactionStatus = true;
            try {
                $em->persist($contact);
                $em->persist($address);
            } catch (Exception $e) {
                $transactionStatus = false;
            }
            if($transactionStatus){
                $em->flush();
                $this->addFlash('notice', 'Merci pour votre opinion passionante, votre message ne sera pas traité dans les plus brefs délais :)');
                $mail = \Swift_Message::newInstance()
                    ->setSubject('Une demande de contact à été passée')
                    ->setFrom($contact->getEmail())
                    ->setTo('admin@mpowker4.game')
                    ->setBody(
                        $this->renderView(
                            'Emails/contact.html.twig',
                            array('contact' => $contact)
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($mail);

                return $this->redirect($this->generateUrl('powker4_index'));
            }
        }

        return $this->render('Powker4Bundle:Powker4:contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function adminContactAction(Request $request)
    {
        $em = $this->getdoctrine()->getManager();
        $contacts = $em->getRepository('Powker4Bundle:Contact')->findAll();
        $contactform = [];

        forEach($contacts as $contact){
            $formContact = $this->createform(new ContactType(), $contact, [
                'methode'=> 'post',
                'action'=>$this->generateUrl('powker4_admin_contact', [
                    'id' => $contact->getId()
                ]),
            ]);
            $contactForm[] = $formContact->createView();
        }
        return $this->render('Powker4Bundle:Powker4:adminContact.html.twig', [
            'contact' => $contacts,
            'contactForm' => $contactForm,
        ]);
    }
}
