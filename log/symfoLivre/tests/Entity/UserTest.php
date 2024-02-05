<?php

namespace App\Tests;
use App\Entity\User;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
    public function testIsTrue() // je fais à VRAI
    {

        $user = New User();
        
        $user ->setNomUser('Nom User')
                    ->setMailUser('Mail User');         
                    ->setPasswordUser('Password User');         
                    ->setRoleUser('Role User');         

                    $this->assertTrue($user->getNomUser()==='Nom de User');
        $this->assertTrue($user->getMailUser()==='Mail User');            
        $this->assertTrue($user->getPasswordUser()==='Password User');            
        $this->assertTrue($user->getRoleUser()==='Role User');            

    }


}
