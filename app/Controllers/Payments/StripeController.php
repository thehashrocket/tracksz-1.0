<?php

declare(strict_types=1);

namespace App\Controllers\Payments;

use Stripe\Stripe;
use Stripe\Customer;

class StripeController
{
    /**
     * _construct - create object
     *
     * @return no return
     */
    public function __construct()
    {
        $key = getenv('STRIPE_SECRET');
        Stripe::setApiKey($key);
    }
    /**
     * createMember - used first time member adds a card.  Gets' new customer id at Stripe.
     *
     * @param  $form Array with name, address, address1, zipcode, and stripetoken
     * @param  $email - email address of customer
     * @return no return
     */
    public function createMember($form, $email)
    {
        $valid = true;
        try {
            $result = $customer = \Stripe\Customer::create([
                'name' => $form['FullName'],
                'description' => $form['FullName'],
                'address' => [
                    'line1' => $form['Address1'],
                    'line2' => $form['Address2'],
                    'postal_code' => $form['PostCode'],
                    'city' => $form['City'],
                    'state' => $form['Zone'],
                    'country' => $form['Country']
                ],
                'email' => $email
            ]);
        } catch (\Stripe\Error\Card $e) {
            $valid = false;
        } catch (\Stripe\Error\RateLimit $e) {
            $valid = false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $valid = false;
        } catch (\Stripe\Error\Authentication $e) {
            $valid = false;
        } catch (\Stripe\Error\ApiConnection $e) {
            $valid = false;
        } catch (\Stripe\Error\Base $e) {
            $valid = false;
        } catch (Exception $e) {
            $valid = false;
        }
        if (!$valid) {
            return false;
        }
        return $result;
    }

    /**
     * addMemberCard - add a card to a current stripe member
     *
     * @param  $form Array with name, address, address1, zipcode, and stripetoken
     * @param  $id - customer id from stripe
     * @return a call to updateCustomerCard to add name, address, zip to new card
     */
    public function addMemberCard($form, $id)
    {
        $valid = true;

        $customer = Customer::retrieve($id);
        $duplicate = $this->duplicateCard($customer, $id, $form['stripeToken'], false);
        if (!$duplicate) {
            try {
                $result = $customer = \Stripe\Customer::createSource(
                    $id,
                    ['source' => $form['stripeToken']]
                );
            } catch (\Stripe\Error\Card $e) {
                $valid = false;
            } catch (\Stripe\Error\RateLimit $e) {
                $valid = false;
            } catch (\Stripe\Error\InvalidRequest $e) {
                $valid = false;
            } catch (\Stripe\Error\Authentication $e) {
                $valid = false;
            } catch (\Stripe\Error\ApiConnection $e) {
                $valid = false;
            } catch (\Stripe\Error\Base $e) {
                $valid = false;
            } catch (Exception $e) {
                $valid = false;
            }
        } else {
            return $duplicate;
        }
        if (!$valid) {
            return false;
        }

        return $result;
    }


    /**
     * updateCardDetails - called after Add Card to add name, address, zip, email to
     *                     a new card.  For some reason when adding a card to a current customer
     *                     with a card at stripe, you cannot easily send the address, name, and
     *                     zip information. At least I couldn't figure it out.  call update to add.
     *
     * @param  $form Array with name, address, address1, zipcode, and stripetoken
     * @param  $id - customer stripe id
     * @param  $result - the result from adding the new card from function above.
     * @return return result.
     */
    public function updateCardDetails($id, $form, $paymentId)
    {
        $valid = true;
        $update = [
            'name'          => $form['FullName'],
            'address_line1' => $form['Address1'],
            'address_line2' => $form['Address2'],
            'address_city'  => $form['City'],
            'address_state' => $form['Zone'],
            'address_country' => $form['Country'],
            'address_zip'   => $form['PostCode']
        ];
        if (isset($form['exp_month'])) {
            $update['exp_month'] = $form['exp_month'];
        }
        if (isset($form['exp_year'])) {
            $update['exp_year'] = $form['exp_year'];
        }
        try {
            $result = $customer = \Stripe\Customer::updateSource(
                $id,
                $paymentId,
                $update
            );
        } catch (\Stripe\Error\Card $e) {
            $valid = false;
        } catch (\Stripe\Error\RateLimit $e) {
            $valid = false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $valid = false;
        } catch (\Stripe\Error\Authentication $e) {
            $valid = false;
        } catch (\Stripe\Error\ApiConnection $e) {
            $valid = false;
        } catch (\Stripe\Error\Base $e) {
            $valid = false;
        } catch (Exception $e) {
            $valid = false;
        }
        if (!$valid) {
            return false;
        }
        return $result;
    }

    /**
     * deleteCustomerCard - delete a card to a current stripe customers
     *
     * @param  $paymentId - payment id recorded at gateway (stripe)
     * @param  $gatewayUserId - user id recorded at gateway (strip)
     * @return no return
     */
    public function deleteCustomerCard($gatewayUserId, $paymentId)
    {
        $valid = true;
        try {
            $result = $customer = \Stripe\Customer::deleteSource(
                $gatewayUserId,
                $paymentId
            );
        } catch (\Stripe\Error\Card $e) {
            $valid = false;
        } catch (\Stripe\Error\RateLimit $e) {
            $valid = false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $valid = false;
        } catch (\Stripe\Error\Authentication $e) {
            $valid = false;
        } catch (\Stripe\Error\ApiConnection $e) {
            $valid = false;
        } catch (\Stripe\Error\Base $e) {
            $valid = false;
        } catch (Exception $e) {
            $valid = false;
        }
        if (!$valid) {
            return false;
        }
        return $result;
    }

    /**
     * retrieveCards - retrieve all cards for a user from gateway
     *
     * @param  $gatewayUserId - user id recorded at gateway (strip)
     * @return result from gateway query
     */
    public function retrieveCards($gatewayUserId)
    {
        $valid = true;
        try {
            $result = $customer = \Stripe\Customer::allSources(
                $gatewayUserId
            );
        } catch (\Stripe\Error\Card $e) {
            $valid = false;
        } catch (\Stripe\Error\RateLimit $e) {
            $valid = false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $valid = false;
        } catch (\Stripe\Error\Authentication $e) {
            $valid = false;
        } catch (\Stripe\Error\ApiConnection $e) {
            $valid = false;
        } catch (\Stripe\Error\Base $e) {
            $valid = false;
        } catch (Exception $e) {
            $valid = false;
        }
        if (!$valid) {
            return false;
        }
        return $result;
    }

    /**
     * retrieveCard - retrieve one card for a user from gateway
     *
     * @param  $gatewayUserId - user id recorded at gateway (strip)
     * @return result from gateway query
     */
    public function retrieveCard($gatewayUserId, $gatewayPaymentId)
    {
        $valid = true;
        try {
            $result = \Stripe\Customer::retrieveSource($gatewayUserId, $gatewayPaymentId);
        } catch (\Stripe\Error\Card $e) {
            $valid = false;
        } catch (\Stripe\Error\RateLimit $e) {
            $valid = false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $valid = false;
        } catch (\Stripe\Error\Authentication $e) {
            $valid = false;
        } catch (\Stripe\Error\ApiConnection $e) {
            $valid = false;
        } catch (\Stripe\Error\Base $e) {
            $valid = false;
        } catch (Exception $e) {
            $valid = false;
        }
        if (!$valid) {
            return false;
        }
        return $result;
    }


    /**
     * error - decypher error, create error array and return
     *
     * @param  $err - error message from Exceptoin in function
     * @return return error array
     */
    public function error($err)
    {
        $error['valid'] = false;
        $error['error'] =  $err['message'];
        return $error;
    }


    function duplicateCard($customer, $stripe_account, $token, $check_exp)
    {
        try {
            // get token data
            $response = \Stripe\Token::retrieve(
                $token
            );
            $token_fingerprint = $response->card->fingerprint;
            $token_exp_month = $response->card->exp_month;
            $token_exp_year = $response->card->exp_year;
            $duplicate_found = false;
            foreach ($customer->sources->data as &$value) {
                // get data
                $fingerprint = $value->fingerprint;
                $exp_month = $value->exp_month;
                $exp_year = $value->exp_year;

                if ($fingerprint == $token_fingerprint) {
                    if ($check_exp) {
                        if (($exp_month == $token_exp_month) && ($exp_year == $token_exp_year)) {
                            $duplicate_found = true;
                            break;
                        }
                    } else {
                        $duplicate_found = $value->id;
                        break;
                    }
                }
            }
        } catch (Exception $e) {
            // should do something here
        }
        return $duplicate_found;
    }
}
