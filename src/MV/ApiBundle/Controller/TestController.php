<?php

namespace MV\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TestController extends Controller
{
    /**
     * @Route("test", name="test.index")
     */
    public function indexAction()
    {
        $url = 'http://localhost/v1/users?_format=json';

        // create a new cURL resource
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        // grab URL and pass it to the browser
        $result = curl_exec($ch);

        if (curl_exec($ch) === false) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // close cURL resource, and free up system resources
        curl_close($ch);

        echo($result);

        exit;
    }
}
