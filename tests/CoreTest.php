<?php

namespace Keruald;

class CoreTest extends \PHPUnit_Framework_Testcase {

    ///
    /// Client information
    ///

    function test_extract_client_ip_from_header () {
        $values = [
            //Each value should return 10.0.0.3
            '10.0.0.3',
            '10.0.0.3,10.0.0.4',
            '10.0.0.3, 10.0.0.4',
            '10.0.0.3, 10.0.0.4, lorem ipsum dolor',
        ];
        foreach ($values as $value) {
            $this->assertEquals(
                '10.0.0.3',
                extract_client_ip_from_header($value)
            );
        }

        $this->assertEmpty(
            extract_client_ip_from_header('')
        );
    }

    function test_get_remote_addr () {
        $this->assertEmpty(get_remote_addr());

        $_SERVER = [
            'REMOTE_ADDR' => '10.0.0.2'
        ];
        $this->assertEquals('10.0.0.2', get_remote_addr());

        $_SERVER += [
            'HTTP_X_FORWARDED_FOR' => '10.0.0.3',
            'HTTP_CLIENT_IP' => '10.0.0.4',
        ];
        $this->assertEquals('10.0.0.3', get_remote_addr(), "HTTP_X_FORWARDED_FOR must be prioritized.");
    }
}
