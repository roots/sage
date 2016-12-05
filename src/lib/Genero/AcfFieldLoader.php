<?php

namespace Genero\Sage;

/**
 * ACF Field loader which only loads field groups if they do not exist.
 */
class AcfFieldLoader
{
    protected static function saveFieldgroupToDatabase($groupKey)
    {
        $fieldGroup = acf_get_field_group($groupKey);
        $fields = acf_get_fields($fieldGroup);
        // Remove the attribute that says this is a synced fieldgroup.
        unset($fieldGroup['local']);

        // Save the group to get a post id.
        $fieldGroup = acf_update_field_group($fieldGroup);
        // Create the fields.
        foreach ($fields as $field) {
            $field['parent'] = $fieldGroup['ID'];
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
