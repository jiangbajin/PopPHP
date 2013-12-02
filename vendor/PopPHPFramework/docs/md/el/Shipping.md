Pop PHP Framework
=================

Documentation : Shipping
------------------------

Home

Το συστατικό Shipping παρέχει τυποποιημένη λειτουργικότητα για να συγκρίνετε τιμές ναυτιλίας μεταξύ δύο διευθύνσεων μέσω των τυποποιημένων UPS και FedEx APIs. Εάν απαιτείται ένα διαφορετικό προσαρμογέα ναυτιλία, κάποιος μπορεί εύκολα να γραφτεί και να ενσωματωθούν.

    use Pop\Shipping\Shipping;
    use Pop\Shipping\Adapter\Ups;
    use Pop\Shipping\Adapter\Fedex;

    $shipping = new Shipping(new Ups('ACCESS_KEY', 'USER_ID', 'PASSWORD'));
    // -- OR --
    //$shipping = new Shipping(new Fedex('KEY', 'PASSWORD', 'ACCT_NUM', 'METER_NUM'));

    $shipping->shipTo(array(
        'company'  => 'Some Company',
        'address1' => '123 Main St.',
        'address2' => 'Suite A',
        'city'     => 'Metairie',
        'state'    => 'LA',
        'zip'      => '70002',
        'country'  => 'US'
    ));

    $shipping->shipFrom(array(
        'company'  => 'My Company',
        'address1' => '456 Main St.',
        'city'     => 'New Orleans',
        'state'    => 'LA',
        'zip'      => '70124',
        'country'  => 'US'
    ));

    $shipping->setDimensions(array(
        'length' => 12,
        'height' => 3,
        'width'  => 6
    ));

    $shipping->setWeight(5);

    $shipping->send();

    if ($shipping->isSuccess()) {
        foreach ($shipping->getRates() as $rate => $cost) {
            echo $rate . ': $' . $cost;
        }
    } else {
        echo $shipping->getResponseCode() . ' : ' . $shipping->getResponseMessage();
    }

\(c) 2009-2013 [Moc 10 Media, LLC.](http://www.moc10media.com) All
Rights Reserved.