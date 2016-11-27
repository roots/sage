<?php

namespace Genero\Sage;

/**
 * ACF Field loader which only loads field groups if they do not exist.
 */
class AcfFieldLoader
{
    protected static function saveFieldgroupToDatabase($group_key) {
        $field_group = acf_get_field_group($group_key);
        $fields = acf_get_fields($field_group);
        // Remove the attribute that says this is a synced fieldgroup.
        unset($field_group['local']);

        // Save the group to get a post id.
        $field_group = acf_update_field_group($field_group);
        // Create the fields.
        foreach ($fields as $field) {
            $field['parent'] = $field_group['ID'];
            $subfields = acf_get_fields($field);
            // Save the field to get the post id from the subfields.
            $field = acf_update_field($field);
            // Create the subfields.
            if (!empty($subfields)) {
                foreach ($subfields as $subfield) {
                    $subfield['parent'] = $field['ID'];
                    $subfield = acf_update_field($subfield);
                }
            }
        }
    }
}
