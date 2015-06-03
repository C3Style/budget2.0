<?php

namespace C3\BudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use C3\BudgetBundle\Entity\Transaction;

class TransactionController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('C3\BudgetBundle\Entity\Transaction');

        $transaction = new Transaction();
        $transaction->setDate(new \DateTime('14-02-2012', new \DateTimeZone('Europe/Paris')));
        $transaction->setAmount('19.99');
        $transaction->setDescription('Lorem ipsum dolor');
        // $em = $this->getDoctrine()->getManager();
        // $em->persist($transaction);
        // $em->flush();

        $transactions = $repository->findAll();

        return $this->render('C3BudgetBundle:Transaction:index.html.twig', array('transactions' => $transactions));
    }
}
