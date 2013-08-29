<?php

class HTMLPurifier_ElementDefTest extends HTMLPurifier_Harness
{

    function test_mergeIn() {

        $def1 = new HTMLPurifier_ElementDef();
        $def2 = new HTMLPurifier_ElementDef();
        $def3 = new HTMLPurifier_ElementDef();

        $old = 1;
        $new = 2;
        $overloaded_old = 3;
        $overloaded_new = 4;
        $removed = 5;

        $def1->standalone = true;
        $def1->attr = array(
            0 => array('old-include'),
            'old-attr' => $old,
            'overloaded-attr' => $overloaded_old,
            'removed-attr' => $removed,
        );
        /*
        $def1->attr_transform_pre =
        $def1->attr_transform_post = array(
            'old-transform' => $old,
            'overloaded-transform' => $overloaded_old,
            'removed-transform' => $removed,
        );
         */
        $def1->attr_transform_pre[] = $old;
        $def1->attr_transform_post[] = $old;
        $def1->child = $overloaded_old;
        $def1->content_model = 'old';
        $def1->content_model_type = $overloaded_old;
        $def1->descendants_are_inline = false;
        $def1->excludes = array(
            'old' => true,
            'removed-old' => true
        );

        $def2->standalone = false;
        $def2->attr = array(
            0 => array('new-include'),
            'new-attr' => $new,
            'overloaded-attr' => $overloaded_new,
            'removed-attr' => false,
        );
        /*
        $def2->attr_transform_pre =
        $def2->attr_transform_post = array(
            'new-transform' => $new,
            'overloaded-transform' => $overloaded_new,
            'removed-transform' => false,
        );
         */
        $def2->attr_transform_pre[] = $new;
        $def2->attr_transform_post[] = $new;
        $def2->child = $new;
        $def2->content_model = '#SUPER | new';
        $def2->content_model_type = $overloaded_new;
        $def2->descendants_are_inline = true;
        $def2->excludes = array(
            'new' => true,
            'removed-old' => false
        );

        $def1->mergeIn($def2);
        $def1->mergeIn($def3); // empty, has no effect

        $this->assertIdentical($def1->standalone, true);
        $this->assertIdentical($def1->attr, array(
            0 => array('old-include', 'new-include'),
            'old-attr' => $old,
            'overloaded-attr' => $overloaded_new,
            'new-attr' => $new,
        ));
        $this->assertIdentical($def1->attr_transform_pre, $def1->attr_transform_post);
        $this->assertIdentical($def1->attr_transform_pre, array($old, $new));
        /*
        $this->assertIdentical($def1->attr_transform_pre, array(
            'old-transform' => $old,
            'overloaded-transform' => $overloaded_new,
            'new-transform' => $new,
        ));
         */
        $this->assertIdentical($def1->child, $new);
        $this->assertIdentical($def1->content_model, 'old | new');
        $this->assertIdentical($def1->content_model_type, $overloaded_new);
        $this->assertIdentical($def1->descendants_are_inline, true);
        $this->assertIdentical($def1->excludes, array(
            'old' => true,
            'new' => true
        ));

    }

}

// vim: et sw=4 sts=4
