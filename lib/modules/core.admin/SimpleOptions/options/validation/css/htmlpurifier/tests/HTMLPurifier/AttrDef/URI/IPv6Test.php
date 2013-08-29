<?php

// test case is from Feyd's IPv6 implementation
// we ought to disallow non-routable addresses

class HTMLPurifier_AttrDef_URI_IPv6Test extends HTMLPurifier_AttrDefHarness
{

    function test() {

        $this->def = new HTMLPurifier_AttrDef_URI_IPv6();

        $this->assertDef('2001:DB8:0:0:8:800:200C:417A'); // unicast, full
        $this->assertDef('FF01:0:0:0:0:0:0:101'); // multicast, full
        $this->assertDef('0:0:0:0:0:0:0:1'); // loopback, full
        $this->assertDef('0:0:0:0:0:0:0:0'); // unspecified, full
        $this->assertDef('2001:DB8::8:800:200C:417A'); // unicast, compressed
        $this->assertDef('FF01::101'); // multicast, compressed

        $this->assertDef('::1'); // loopback, compressed, non-routable
        $this->assertDef('::'); // unspecified, compressed, non-routable
        $this->assertDef('0:0:0:0:0:0:13.1.68.3'); // IPv4-compatible IPv6 address, full, deprecated
        $this->assertDef('0:0:0:0:0:FFFF:129.144.52.38'); // IPv4-mapped IPv6 address, full
        $this->assertDef('::13.1.68.3'); // IPv4-compatible IPv6 address, compressed, deprecated
        $this->assertDef('::FFFF:129.144.52.38'); // IPv4-mapped IPv6 address, compressed
        $this->assertDef('2001:0DB8:0000:CD30:0000:0000:0000:0000/60'); // full, with prefix
        $this->assertDef('2001:0DB8::CD30:0:0:0:0/60'); // compressed, with prefix
        $this->assertDef('2001:0DB8:0:CD30::/60'); // compressed, with prefix #2
        $this->assertDef('::/128'); // compressed, unspecified address type, non-routable
        $this->assertDef('::1/128'); // compressed, loopback address type, non-routable
        $this->assertDef('FF00::/8'); // compressed, multicast address type
        $this->assertDef('FE80::/10'); // compressed, link-local unicast, non-routable
        $this->assertDef('FEC0::/10'); // compressed, site-local unicast, deprecated

        $this->assertDef('2001:DB8:0:0:8:800:200C:417A:221', false); // unicast, full
        $this->assertDef('FF01::101::2', false); //multicast, compressed
        $this->assertDef('', false); // nothing

    }

}

// vim: et sw=4 sts=4
