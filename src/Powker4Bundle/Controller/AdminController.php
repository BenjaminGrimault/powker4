<?php

namespace Powker4Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Powker4Bundle\Entity\Contact;
use Powker4Bundle\Entity\Address;
use Powker4Bundle\Form\ContactType;
use Powker4Bundle\Form\ContactAdminType;

class AdminController extends Controller
{
    public function contactAction(Request $request)
    {
        $em = $this->getdoctrine()->getManager();
        $contacts = $em->getRepository('Powker4Bundle:Contact')->findAll();
        $contactForms = [];

        foreach($contacts as $contact){
            $formContact = $this->createform(new ContactAdminType(), $contact, [
                'method'=> 'post',
                'action'=>$this->generateUrl('powker4_admin_contact', [
                    'id' => $contact->getId()
                ]),
            ]);
            $contactForms[] = $formContact->createView();
        }
        return $this->render('Powker4Bundle:Admin:contact.html.twig', [
            'contacts' => $contacts,
            'contactForms' => $contactForms,
        ]);
    }
}
