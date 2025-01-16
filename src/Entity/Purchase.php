<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $purchaseNumber;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $objectOfPurchase;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $initialPrice;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $EISPostedDate;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $EPPostedDate;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $status;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $protocolName;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $protocolOrganizationName;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $EANotice;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $EALocation;

    #[ORM\Column(type: 'string')]
    private ?string $protocolCreationDate;

    #[ORM\Column(type: 'string')]
    private ?string $protocolSigningDate;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $commission;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $commission44FZisAuthorized;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $commissionMembersNumber;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $commissionNoVoteMembersNumber;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $commissionPresentMembersNumber;

    #[ORM\Column(type: 'text')]
    private ?string $documentLinks;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $updated_at;

    #[ORM\JoinTable(name: 'participants_purchase')]
    #[ORM\JoinColumn(name: 'purchase_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\InverseJoinColumn(name: 'participant_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToMany(targetEntity: Participant::class, inversedBy: 'purchases')]
    private Collection $participants;

    public function __construct()
    {
        $this->setUpdatedAt(new \DateTime('now'));
        $this->setCreatedAt(new \DateTime('now'));

        $this->participants = new ArrayCollection();
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addPurchase($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            $participant->removePurchase($this);
        }

        return $this;
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPurchaseNumber(): ?string
    {
        return $this->purchaseNumber;
    }

    public function setPurchaseNumber(?string $purchaseNumber): void
    {
        $this->purchaseNumber = $purchaseNumber;
    }

    public function getObjectOfPurchase(): ?string
    {
        return $this->objectOfPurchase;
    }

    public function setObjectOfPurchase(?string $objectOfPurchase): void
    {
        $this->objectOfPurchase = $objectOfPurchase;
    }

    public function getInitialPrice(): ?string
    {
        return $this->initialPrice;
    }

    public function setInitialPrice(?string $initialPrice): void
    {
        $this->initialPrice = $initialPrice;
    }

    public function getEISPostedDate(): ?string
    {
        return $this->EISPostedDate;
    }

    public function setEISPostedDate(?string $EISPostedDate): void
    {
        $this->EISPostedDate = $EISPostedDate;
    }

    public function getEPPostedDate(): ?string
    {
        return $this->EPPostedDate;
    }

    public function setEPPostedDate(?string $EPPostedDate): void
    {
        $this->EPPostedDate = $EPPostedDate;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getProtocolName(): ?string
    {
        return $this->protocolName;
    }

    public function setProtocolName(?string $protocolName): void
    {
        $this->protocolName = $protocolName;
    }

    public function getProtocolOrganizationName(): ?string
    {
        return $this->protocolOrganizationName;
    }

    public function setProtocolOrganizationName(?string $protocolOrganizationName): void
    {
        $this->protocolOrganizationName = $protocolOrganizationName;
    }

    public function getEANotice(): ?string
    {
        return $this->EANotice;
    }

    public function setEANotice(?string $EANotice): void
    {
        $this->EANotice = $EANotice;
    }

    public function getEALocation(): ?string
    {
        return $this->EALocation;
    }

    public function setEALocation(?string $EALocation): void
    {
        $this->EALocation = $EALocation;
    }

    public function getProtocolCreationDate(): ?string
    {
        return $this->protocolCreationDate;
    }

    public function setProtocolCreationDate(?string $protocolCreationDate): void
    {
        $this->protocolCreationDate = $protocolCreationDate;
    }

    public function getProtocolSigningDate(): ?string
    {
        return $this->protocolSigningDate;
    }

    public function setProtocolSigningDate(?string $protocolSigningDate): void
    {
        $this->protocolSigningDate = $protocolSigningDate;
    }

    public function getCommission(): ?string
    {
        return $this->commission;
    }

    public function setCommission(?string $commission): void
    {
        $this->commission = $commission;
    }

    public function getCommission44FZisAuthorized(): ?string
    {
        return $this->commission44FZisAuthorized;
    }

    public function setCommission44FZisAuthorized(?string $commission44FZisAuthorized): void
    {
        $this->commission44FZisAuthorized = $commission44FZisAuthorized;
    }

    public function getCommissionMembersNumber(): ?string
    {
        return $this->commissionMembersNumber;
    }

    public function setCommissionMembersNumber(?string $commissionMembersNumber): void
    {
        $this->commissionMembersNumber = $commissionMembersNumber;
    }

    public function getCommissionNoVoteMembersNumber(): ?string
    {
        return $this->commissionNoVoteMembersNumber;
    }

    public function setCommissionNoVoteMembersNumber(?string $commissionNoVoteMembersNumber): void
    {
        $this->commissionNoVoteMembersNumber = $commissionNoVoteMembersNumber;
    }

    public function getCommissionPresentMembersNumber(): ?string
    {
        return $this->commissionPresentMembersNumber;
    }

    public function setCommissionPresentMembersNumber(?string $commissionPresentMembersNumber): void
    {
        $this->commissionPresentMembersNumber = $commissionPresentMembersNumber;
    }

    public function getDocumentLinks(): ?string
    {
        return $this->documentLinks;
    }

    public function setDocumentLinks(?string $documentLinks): void
    {
        $this->documentLinks = $documentLinks;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getPurchases()
    {
        return $this->purchases;
    }

    /**
     * @param mixed $purchases
     */
    public function setPurchases($purchases): void
    {
        $this->purchases = $purchases;
    }

}