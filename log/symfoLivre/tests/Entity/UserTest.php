<?php

namespace App\Tests;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
    public function testIsTrue(UserPasswordHasherInterface $passwordHasher) // je fais à VRAI
    {
        $user = new User($passwordHasher); // Fournir le service UserPasswordHasherInterface ici

        $user ->setNomUser('Nom User')
                    ->setMailUser('Mail User')         
                    ->setPasswordUser('PasswordUser')         
                    ->setRoleUser('Role User');         

                    $this->assertTrue($user->getNomUser()==='Nom de User');
        $this->assertTrue($user->getMailUser()==='Mail User');            
        $this->assertTrue($user->getPasswordUser()==='Password User');            
        $this->assertTrue($user->getRoleUser()==='Role User');            

    }


}
