<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Categorie;


class CategorieTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
    public function testIsTrue() // je fais à VRAI
    {

        $categorie = New Categorie();
        
        $categorie ->setNomCategorie('Nom de la categorie')
                    ->setInformationCategorie('Les informations de la categorie');         
        $this->assertTrue($categorie->getNomCategorie()==='Nom de la categorie');
        $this->assertTrue($categorie->getInformationCategorie()==='Les informations de la categorie');            
    }

     public function testIsEmpty()
    {
        $categorie = new Categorie();
        $this->assertEmpty($categorie->getNomCategorie());
        $this->assertEmpty($categorie->getInformationCategorie() );
        $this->assertEmpty($categorie->getId());
    }

}
