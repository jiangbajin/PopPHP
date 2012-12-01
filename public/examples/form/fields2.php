<?php
require_once '../../bootstrap.php';

use Pop\Db\Db,
    Pop\Form\Form,
    Pop\Form\Fields,
    Pop\Form\Element,
    Pop\Record\Record;

class Users extends Record { }

class User extends Form { }

try {
    // Define DB credentials
    $db = Db::factory('Mysqli', array(
        'database' => 'helloworld',
        'host'     => 'localhost',
        'username' => 'hellow',
        'password' => '12world34'
    ));

    Users::setDb($db);

    $attribs = array(
        'text'     => array('size', 40),
        'password' => array('size', 20),
        'textarea' => array(array('rows', 5), array('cols', 80))
    );

    $values = array(
        'id' => array(
            'type' => 'hidden'
        ),
        'username' => array(
            'value'      => 'Enter Username...',
            'validators' => new Pop\Validator\Validator\AlphaNumeric()
        ),
        'allowed_sites' => array(
            'type'   => 'checkbox',
            'value'  => array('2001' => 'test1.localhost', '2002' => 'test2.localhost'),
            'marked' => array('2001', '2002')
        ),
        'access_id' => array(
            'type'   => 'select',
            'value'  => array('3001' => 'Admin', '3002' => 'Basic'),
            'marked' => '3002'
        )
    );

    $fields = Fields::factory(
        new Users(),
        $attribs,
        $values,
        array('last_login', 'last_ua', 'last_ip', 'failed_attempts')
    );

    $fields->addFields(array(
        'type'  => 'submit',
        'name'  => 'submit',
        'label' => '&nbsp;',
        'value' => 'SUBMIT',
    ));

    $form = new User($_SERVER['REQUEST_URI'], 'post', $fields->getFields());

    if ($_POST) {
        $form->setFieldValues($_POST);
        if ($form->isValid()) {
            echo 'Form is valid!';
        } else {
            $form->render();
        }
    } else {
        $form->render();
    }

    echo PHP_EOL . PHP_EOL;
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL . PHP_EOL;
}