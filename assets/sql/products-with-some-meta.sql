SELECT 
    *
FROM 
    afcweb_posts p
    JOIN afcweb_postmeta pm ON pm.post_id = p.ID
    JOIN afcweb_postmeta tabs_1_tab_label ON (tabs_1_tab_label.post_id = p.ID AND tabs_1_tab_label.meta_key = 'tabs_1_tab_label')
    JOIN afcweb_postmeta tabs_2_tab_label ON (tabs_2_tab_label.post_id = p.ID AND tabs_2_tab_label.meta_key = 'tabs_2_tab_label')
    JOIN afcweb_postmeta tabs_1_tab_content ON (tabs_1_tab_content.post_id = p.ID AND tabs_1_tab_content.meta_key = 'tabs_1_tab_content')
WHERE 
    tabs_1_tab_label.meta_value = 'Overview'
    AND p.post_type = 'product'
GROUP BY p.ID
ORDER BY p.post_title ASC


