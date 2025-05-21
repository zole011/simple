<?php

declare(strict_types=1);

namespace Gmbit\Simple\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Member extends AbstractEntity
{
    protected string $name = '';
    protected string $prefix = '';
    protected string $prezime = '';
    protected string $funkcija = '';
    protected string $zvanje = '';
    protected string $oblast = '';
    protected string $konsultacije = '';
    protected string $email = '';
    protected string $biografija = '';
    protected string $radovi = '';
    protected string $udzbenici = '';
    protected string $sortiranje = '';
    protected string $group = '';

    protected ?FileReference $cv = null;
    protected ?FileReference $karton = null;
    protected ?FileReference $image = null;

    // Getters
    public function getName(): string { return $this->name; }
    public function getPrefix(): string { return $this->prefix; }
    public function getPrezime(): string { return $this->prezime; }
    public function getFunkcija(): string { return $this->funkcija; }
    public function getZvanje(): string { return $this->zvanje; }
    public function getOblast(): string { return $this->oblast; }
    public function getKonsultacije(): string { return $this->konsultacije; }
    public function getEmail(): string { return $this->email; }
    public function getBiografija(): string { return $this->biografija; }
    public function getRadovi(): string { return $this->radovi; }
    public function getUdzbenici(): string { return $this->udzbenici; }
    public function getSortiranje(): string { return $this->sortiranje; }
    public function getCv(): ?FileReference { return $this->cv; }
    public function getKarton(): ?FileReference { return $this->karton; }
    public function getImage(): ?FileReference { return $this->image; }
    public function getGroup(): string { return $this->group; }

    // Setters
    public function setName(string $name): void { $this->name = $name; }
    public function setPrefix(string $prefix): void { $this->prefix = $prefix; }
    public function setPrezime(string $prezime): void { $this->prezime = $prezime; }
    public function setFunkcija(string $funkcija): void { $this->funkcija = $funkcija; }
    public function setZvanje(string $zvanje): void { $this->zvanje = $zvanje; }
    public function setOblast(string $oblast): void { $this->oblast = $oblast; }
    public function setKonsultacije(string $konsultacije): void { $this->konsultacije = $konsultacije; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setBiografija(string $biografija): void { $this->biografija = $biografija; }
    public function setRadovi(string $radovi): void { $this->radovi = $radovi; }
    public function setUdzbenici(string $udzbenici): void { $this->udzbenici = $udzbenici; }
    public function setSortiranje(string $sortiranje): void { $this->sortiranje = $sortiranje; }
    public function setCv(?FileReference $cv): void { $this->cv = $cv; }
    public function setKarton(?FileReference $karton): void { $this->karton = $karton; }
    public function setImage(?FileReference $image): void { $this->image = $image; }
    public function setGroup(string $group): void { $this->group = $group;}
}
