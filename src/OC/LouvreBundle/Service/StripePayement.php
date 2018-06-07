<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 29/05/2018
 * Time: 13:52
 */

namespace OC\LouvreBundle\Service;
use Stripe\Stripe;
class StripePayement
{
    private $statut;

    public function procededPayement($token, $commande){

        Stripe::setApiKey("sk_test_GvS4ZcdJiZ22G4hXTjbmaHW1");

        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $commande->getPriceTotal()*100,
                "currency" => "eur",
                "description" => "référence commande : ". $commande->getCodeReservation(),
                'source'  => $token,
            ));
            $this->statut = true;
        } catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];

//            print('Status is:' . $e->getHttpStatus() . "\n");
//            print('Type is:' . $err['type'] . "\n");
//            print('Code is:' . $err['code'] . "\n");
//            // param is '' in this case
//            print('Param is:' . $err['param'] . "\n");
            print('Message is:' . $err['message'] . "\n");
            $this->statut = false;
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
        }
    }

    public function isPaid(){
        return $this->statut;
    }
}