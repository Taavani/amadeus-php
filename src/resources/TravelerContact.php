<?php

declare(strict_types=1);

namespace Amadeus\Resources;

/**
 * Sub-resource in TravelerElement
 * @see TravelerElement::getContact()
 */
class TravelerContact implements ResourceInterface
{
    private ?object $addresseeName = null;
    private ?object $address = null;
    private ?string $language = null;
    private ?string $purpose = null;
    private ?array $phones = null;
    private ?string $companyName = null;
    private ?string $emailAddress = null;

    /**
     * @return TravelerName|null
     */
    public function getAddresseeName(): ?object
    {
        return Resource::toResourceObject(
            $this->addresseeName,
            TravelerName::class
        );
    }

    /**
     * @return TravelerAddress|null
     */
    public function getAddress(): ?object
    {
        return Resource::toResourceObject(
            $this->address,
            TravelerAddress::class
        );
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @return string|null
     */
    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    /**
     * @return TravelerPhone[]|null
     */
    public function getPhones(): ?array
    {
        return Resource::toResourceArray(
            $this->phones,
            TravelerPhone::class
        );
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @return string|null
     */
    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function __set($name, $value): void
    {
        $this->$name = $value;
    }

    public function __toString(): string
    {
        return Resource::toString(get_object_vars($this));
    }

    public function __toArray(): array
    {
        $data = [];
        $data['addresseeName'] = !empty($this->addresseeName) ? $this->addresseeName->__toArray() : null;
        $data['companyName'] = $this->companyName;
        $data['purpose'] = $this->purpose;
	    $data['phones'] = $this->phones ? count($this->phones) > 0 ?array_map(function ($phone) {
		    return $phone->__toArray();
	    }, $this->phones) : null : null;
        $data['emailAddress'] = $this->emailAddress;
        $data['address'] = $this->address?->__toArray();
        $data['language'] = $this->language;

        return $data;
    }
}
