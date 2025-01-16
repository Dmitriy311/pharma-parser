<?php

namespace App\Parser;

use App\Entity\Participant;
use App\Entity\Purchase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class DBpersister
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist($participantsData, $purchaseData): Response
    {
        $purchase = new Purchase();
        $purchase->setPurchaseNumber($purchaseData[0]);
        $purchase->setObjectOfPurchase($purchaseData[1]);
        $purchase->setInitialPrice($purchaseData[2]);
        $purchase->setEISPostedDate($purchaseData[3]);
        $purchase->setEPPostedDate($purchaseData[4]);
        $purchase->setStatus($purchaseData[5]);
        $purchase->setProtocolName($purchaseData[6]);
        $purchase->setProtocolOrganizationName($purchaseData[7]);
        $purchase->setEANotice($purchaseData[8]);
        $purchase->setEALocation($purchaseData[9]);
        $purchase->setProtocolCreationDate($purchaseData[10]);
        $purchase->setProtocolSigningDate($purchaseData[11]);
        $purchase->setCommission($purchaseData[12]);
        $purchase->setCommission44FZisAuthorized($purchaseData[13]);
        $purchase->setCommissionMembersNumber($purchaseData[14]);
        $purchase->setCommissionNoVoteMembersNumber($purchaseData[15]);
        $purchase->setCommissionPresentMembersNumber($purchaseData[16]);
        $purchase->setDocumentLinks($purchaseData[17]);

        foreach ($participantsData as $v) {

            $participantData = $v[0]['content'];

            $participant = new Participant();
            $participant->setType($participantData[0]);
            $participant->setForm($participantData[1]);
            $participant->setFullName($participantData[2]);
            $participant->setInn($participantData[3]);
            $participant->setKpp($participantData[4]);
            $participant->setMailingAddress($participantData[5]);

            $participant->addPurchase($purchase);
            $purchase->addParticipant($participant);

            $this->entityManager->persist($participant);
        }


        $this->entityManager->persist($purchase);
        $this->entityManager->flush();

        return new Response('Data Saved');
    }
}