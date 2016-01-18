<?php

namespace C3\BudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use C3\BudgetBundle\Entity\Transaction;

class TransactionController extends Controller
{
    /**
     * @param Request $request
     * @param $status
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $status)
    {
        $repository = $this->getDoctrine()
            ->getRepository('C3\BudgetBundle\Entity\Transaction');

        $transactions = $repository->findAll();

        return $this->render('C3BudgetBundle:Transaction:index.html.twig', array('transactions' => $transactions, 'status' => $status));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id)
    {
        $transaction = new Transaction();
        $lblSubmit = $this->get('translator')->trans('transaction.controls.create');
        $status = 'create';

        // Load transaction if id is given
        if ($id) {
            $repository = $this->getDoctrine()
                ->getRepository('C3\BudgetBundle\Entity\Transaction');

            $transaction = $repository->find($id);
            $lblSubmit = $this->get('translator')->trans('transaction.controls.update');
            $status = 'update';
        }

        // Create the form
        $form = $this->createFormBuilder($transaction)
            ->add('id', 'hidden')
            ->add('date', 'date', array(
                'label' => 'transaction.controls.date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
            ->add('amount', 'money', array(
                'label' => 'transaction.controls.amount',
                'currency' => 'CHF',
            ))
            ->add('description', 'text', array(
                'label' => 'transaction.controls.description',
            ))
            ->add('save', 'submit', array(
                'label' => $lblSubmit,
                'attr' => array('class' => 'btn btn-sm btn-info btn-white'),
                'icon' => 'fa-floppy-o',
            ))
            ->getForm();

        // POST form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $transaction = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->flush();

            return $this->redirectToRoute('c3_budget_homepage', array('status' => $status));
        }

        return $this->render('C3BudgetBundle:Transaction:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if ($id == 0) {
            throw $this->createNotFoundException('Transaction id not found');
        }

        // Load transaction
        $repository = $this->getDoctrine()
            ->getRepository('C3\BudgetBundle\Entity\Transaction');
        $transaction = $repository->find($id);

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($transaction);
        $em->flush();

        return $this->redirectToRoute('c3_budget_homepage', array('status' => 'delete'));
    }
}
