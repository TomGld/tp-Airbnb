<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;
use Symplefony\Model\Entity;

class User extends Entity
{

    /**
     * Rôle admin
     */
    public const ROLE_ADMIN = 3;
    /**
     * Rôle annonceur
     */
    public const ROLE_ANNOUNCER = 2;
    /**
     * Rôle client
     */
    public const ROLE_CUSTOMER = 1;

    protected string $password;
    public function getPassword(): string { return $this->password; }
    public function setPassword( string $value ): self
    {
        $this->password = $value;
        return $this; // Permet de "chaîner" les appels aux setters: $toto->setId(2)->setName('toto'), etc.
    }
   
    protected string $email;
    public function getEmail(): string { return $this->email; }
    public function setEmail( string $value ): self
    {
        $this->email = $value;
        return $this;
    }

    protected string $firstname;
    public function getFirstname(): string { return $this->firstname; }
    public function setFirstname( string $value ): self
    {
        $this->firstname = $value;
        return $this;
    }

    protected string $lastname;
    public function getLastname(): string { return $this->lastname; }
    public function setLastname( string $value ): self
    {
        $this->lastname = $value;
        return $this;
    }

    protected string $phone_number;
    public function getPhoneNumber(): string { return $this->phone_number; }
    public function setPhoneNumber( string $value ): self
    {
        $this->phone_number = $value;
        return $this;
    }
    
    protected int $role;
    public function getRole(){ return $this->role; }
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }
}