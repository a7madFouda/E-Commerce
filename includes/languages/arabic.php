<?php

    function lang( $phrase) {

        static $lang = array(

            'MESSAGE' => 'Welcome in Arabic',
            'ADMIN' => 'moslm'
        );

        return $lang[$phrase];
    }
?>