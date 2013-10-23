SELECT 
    p.*, 
    GROUP_CONCAT(CONCAT(pm.meta_key,':',pm.meta_value) SEPARATOR ',') AS meta_values
FROM 
    wp_posts p
    JOIN wp_postmeta pm ON pm.post_id = p.ID
    JOIN wp_postmeta tabs_1_tab_label ON (tabs_1_tab_label.post_id = p.ID AND tabs_1_tab_label.meta_key = 'tabs_1_tab_label')
    JOIN wp_postmeta tabs_2_tab_label ON (tabs_2_tab_label.post_id = p.ID AND tabs_2_tab_label.meta_key = 'tabs_2_tab_label')
    JOIN wp_postmeta tabs_1_tab_content ON (tabs_1_tab_content.post_id = p.ID AND tabs_1_tab_content.meta_key = 'tabs_1_tab_content')
WHERE 
    tabs_1_tab_label.meta_value = 'Overview'
    AND tabs_2_tab_label.meta_value = 'Specifications'
    AND p.post_type = 'product'
    AND p.post_status = 'publish'
GROUP BY p.ID
ORDER BY p.post_title ASC