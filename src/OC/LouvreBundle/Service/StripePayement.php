<?php
/**
 * Created by PhpStorm.
 * User: gaeta
 * Date: 29/05/2018
 * Time: 13:52
 */

namespace OC\LouvreBundle\Service;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Session\Session;

class StripePayement
{
    private $statut;
    private $secretKey;
    private $publicKey;
    private $session;

    /**
     * StripePayement constructor.
     * @param string $secretKey
     * @param string $publicKey
     * @param Session $session
     */
    public function __construct(string $secretKey, string $publicKey, Session $session)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
        $this->session = $session;
        Stripe::setApiKey($secretKey);
    }

    /** procède au paiment
     * @param $token
     * @param $commande
     */
    public function procededPayement($token, $commande) {

        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $commande->getPriceTotal()*100,
                "currency" => "eur",
                "description" => "référence commande : ". $commande->getCodeReservation(),
                'source'  => $token,
            ));
            $this->statut = $charge->id;
        } catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $body = $e->getJsonBody();
            $err  = $body['error'];

            $this->session->getFlashBag()->add('danger','Message is:' . $err['message']);
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

    /**
     * retourne l'etat de la commande
     * @return mixed
     */
    public function isPaid() {
        return $this->statut;
    }
}