<?php

namespace Keruald;

require 'core.php';

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

    function test_is_ip () {
        $this->assertTrue(is_ip("0.0.0.0"));
        $this->assertFalse(is_ip(""));
        $this->assertFalse(is_ip("1"));
        $this->assertFalse(is_ip("17.17"));
        $this->assertTrue(is_ip("17.17.17.17"));
        $this->assertFalse(is_ip("17.17.17.256"));
        $this->assertTrue(is_ip("fe80:0000:0000:0000:0204:61ff:fe9d:f156"));
    }

    function test_is_ipv4 () {
        $this->assertTrue(is_ipv4("0.0.0.0"));
        $this->assertFalse(is_ipv4(""));
        $this->assertFalse(is_ipv4("1"));
        $this->assertFalse(is_ipv4("17.17"));
        $this->assertTrue(is_ipv4("17.17.17.17"));
        $this->assertFalse(is_ipv4("17.17.17.256"));
        $this->assertFalse(is_ipv4(""));
        $this->assertFalse(is_ipv4("fe80:0000:0000:0000:0204:61ff:fe9d:f156"));
    }

    function test_is_ipv6 () {
        $this->assertFalse(is_ipv6("0.0.0.0"));
        $this->assertFalse(is_ipv6(""));
        $this->assertFalse(is_ipv6("1"));
        $this->assertFalse(is_ipv6("17.17"));
        $this->assertFalse(is_ipv6("17.17.17.17"));
        $this->assertTrue(is_ipv6("::1"));
        $this->assertFalse(is_ipv6("::fg"));
        $this->assertTrue(is_ipv6("::1"));

        //Advanced IPv6 tests curated by Stephen Ryan
        //Source: http://forums.dartware.com/viewtopic.php?t=452
        $this->assertTrue(is_ipv6("fe80:0000:0000:0000:0204:61ff:fe9d:f156"));
        $this->assertFalse(is_ipv6("02001:0000:1234:0000:0000:C1C0:ABCD:0876"), "extra 0 not allowed");
        $this->assertFalse(is_ipv6("2001:0000:1234:0000:00001:C1C0:ABCD:0876"), "extra 0 not allowed");
        $this->assertFalse(is_ipv6("1.2.3.4:1111:2222:3333:4444::5555"));
        $this->assertTrue(is_ipv6("::ffff:192.0.2.128"), "can't validate IPv4 represented as dotted-quads");
    }

    ///
    /// Identifiers
    ///

    function test_uuid () {
        $uuid = uuid();
        $this->assertEquals(36, strlen($uuid));
        for ($i = 0 ; $i < 36 ; $i++) {
            if ($i == 8 | $i == 13 || $i == 18 || $i == 23) {
                $this->assertEquals("-", $uuid[$i], "Dash were expected.");
                continue;
            }

            $this->assertRegExp('/[0-9a-f]/', $uuid[$i], "Lowercase hexadecimal digit were expected.");
        }
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
