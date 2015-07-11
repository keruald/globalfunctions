<?php

namespace Keruald;

class CoreTest extends \PHPUnit_Framework_Testcase {

    ///
    /// Strings
    ///

    function test_mb_str_pad () {
        // Tests from http://3v4l.org/UnXTF
        // http://web.archive.org/web/20150711100913/http://3v4l.org/UnXTF

        $this->assertEquals('àèòàFOOàèòà', mb_str_pad("FOO", 11, "àèò", STR_PAD_BOTH, "UTF-8"));
        $this->assertEquals('àèòFOOàèòà', mb_str_pad("FOO", 10, "àèò", STR_PAD_BOTH, "UTF-8"));
        $this->assertEquals('àèòBAAZàèòà', mb_str_pad("BAAZ", 11, "àèò", STR_PAD_BOTH, "UTF-8"));
        $this->assertEquals('àèòBAAZàèò', mb_str_pad("BAAZ", 10, "àèò", STR_PAD_BOTH, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 6, "àèò", STR_PAD_BOTH, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 1, "àèò", STR_PAD_BOTH, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 0, "àèò", STR_PAD_BOTH, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", -10, "àèò", STR_PAD_BOTH, "UTF-8"));

        $this->assertEquals('àèòàèòàèFOO', mb_str_pad("FOO", 11, "àèò", STR_PAD_LEFT, "UTF-8"));
        $this->assertEquals('àèòàèòàFOO', mb_str_pad("FOO", 10, "àèò", STR_PAD_LEFT, "UTF-8"));
        $this->assertEquals('àèòàèòàBAAZ', mb_str_pad("BAAZ", 11, "àèò", STR_PAD_LEFT, "UTF-8"));
        $this->assertEquals('àèòàèòBAAZ', mb_str_pad("BAAZ", 10, "àèò", STR_PAD_LEFT, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 6, "àèò", STR_PAD_LEFT, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 1, "àèò", STR_PAD_LEFT, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 0, "àèò", STR_PAD_LEFT, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", -10, "àèò", STR_PAD_LEFT, "UTF-8"));

        $this->assertEquals('FOOàèòàèòàè', mb_str_pad("FOO", 11, "àèò", STR_PAD_RIGHT, "UTF-8"));
        $this->assertEquals('FOOàèòàèòà', mb_str_pad("FOO", 10, "àèò", STR_PAD_RIGHT, "UTF-8"));
        $this->assertEquals('BAAZàèòàèòà', mb_str_pad("BAAZ", 11, "àèò", STR_PAD_RIGHT, "UTF-8"));
        $this->assertEquals('BAAZàèòàèò', mb_str_pad("BAAZ", 10, "àèò", STR_PAD_RIGHT, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 6, "àèò", STR_PAD_RIGHT, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 1, "àèò", STR_PAD_RIGHT, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", 0, "àèò", STR_PAD_RIGHT, "UTF-8"));
        $this->assertEquals('FOOBAR', mb_str_pad("FOOBAR", -10, "àèò", STR_PAD_RIGHT, "UTF-8"));
    }

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
