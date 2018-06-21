<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 21/06/2018
 * Time: 11:17
 */

namespace OC\LouvreBundle\Tests\OC\LouvreBundle\Entity;


use OC\LouvreBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testcomputeTVAFoodProduct()
    {
        $product = new Product('Un produit', Product::FOOD_PRODUCT, 20);

        $this->assertSame(1.1, $product->computeTVA());
    }
}