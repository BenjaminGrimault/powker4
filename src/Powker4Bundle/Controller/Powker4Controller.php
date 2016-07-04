<?php

namespace Powker4Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Powker4Bundle\Entity\Contact;
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
            $em->persist($contact);
            $em->flush();

            return $this->redirect($this->generateUrl('powker4_index'));
        }

        return $this->render('Powker4Bundle:Powker4:contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
