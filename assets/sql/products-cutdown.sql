SELECT
afcweb_posts.ID,
afcweb_posts.post_title,
afcweb_posts.post_excerpt,
afcweb_posts.post_content,
header_subtitle.meta_value AS header_subtitle,
custom_permalink.meta_value AS custom_permalink,
tabs.meta_value AS tabs,
tabs_0_tab_label.meta_value AS tabs_0_tab_label,
tabs_0_tab_content.meta_value AS tabs_0_tab_content,
tabs_0_tab_image.meta_value AS tabs_0_tab_image,
tabs_1_tab_label.meta_value AS tabs_1_tab_label,
tabs_1_tab_content.meta_value AS tabs_1_tab_content,
tabs_1_tab_image.meta_value AS tabs_1_tab_image,
tabs_2_tab_label.meta_value AS tabs_2_tab_label,
tabs_2_tab_content.meta_value AS tabs_2_tab_content,
tabs_2_tab_image.meta_value AS tabs_2_tab_image,
tabs_3_tab_label.meta_value AS tabs_3_tab_label,
tabs_3_tab_content.meta_value AS tabs_3_tab_content,
tabs_3_tab_image.meta_value AS tabs_3_tab_image,
tabs_4_tab_label.meta_value AS tabs_4_tab_label,
tabs_4_tab_content.meta_value AS tabs_4_tab_content,
tabs_4_tab_image.meta_value AS tabs_4_tab_image,
tabs_5_tab_label.meta_value AS tabs_5_tab_label,
tabs_5_tab_content.meta_value AS tabs_5_tab_content,
tabs_5_tab_image.meta_value AS tabs_5_tab_image,
tabs_6_tab_label.meta_value AS tabs_6_tab_label,
tabs_6_tab_content.meta_value AS tabs_6_tab_content,
tabs_6_tab_image.meta_value AS tabs_6_tab_image,
tabs_7_tab_label.meta_value AS tabs_7_tab_label,
tabs_7_tab_content.meta_value AS tabs_7_tab_content,
tabs_7_tab_image.meta_value AS tabs_7_tab_image,
_product_image_gallery.meta_value AS _product_image_gallery,
_thumbnail_id.meta_value AS _thumbnail_id,
specifications.meta_value AS specifications,
submittal_sheet.meta_value AS submittal_sheet,
file_downloads.meta_value AS file_downloads,
stp.meta_value AS stp,
sat.meta_value AS sat,
dwg.meta_value AS dwg,
dxf.meta_value AS dxf,
FROM afcweb_posts
LEFT JOIN afcweb_postmeta AS header_subtitle ON (afcweb_posts.ID = header_subtitle.post_id AND header_subtitle.meta_key='header_subtitle')
LEFT JOIN afcweb_postmeta AS custom_permalink ON (afcweb_posts.ID = custom_permalink.post_id AND custom_permalink.meta_key='custom_permalink')
LEFT JOIN afcweb_postmeta AS tabs ON (afcweb_posts.ID = tabs.post_id AND tabs.meta_key='tabs')
LEFT JOIN afcweb_postmeta AS tabs_0_tab_label ON (afcweb_posts.ID = tabs_0_tab_label.post_id AND tabs_0_tab_label.meta_key='tabs_0_tab_label')
LEFT JOIN afcweb_postmeta AS tabs_0_tab_content ON (afcweb_posts.ID = tabs_0_tab_content.post_id AND tabs_0_tab_content.meta_key='tabs_0_tab_content')
LEFT JOIN afcweb_postmeta AS tabs_0_tab_image ON (afcweb_posts.ID = tabs_0_tab_image.post_id AND tabs_0_tab_image.meta_key='tabs_0_tab_image')
LEFT JOIN afcweb_postmeta AS tabs_1_tab_label ON (afcweb_posts.ID = tabs_1_tab_label.post_id AND tabs_1_tab_label.meta_key='tabs_1_tab_label')
LEFT JOIN afcweb_postmeta AS tabs_1_tab_content ON (afcweb_posts.ID = tabs_1_tab_content.post_id AND tabs_1_tab_content.meta_key='tabs_1_tab_content')
LEFT JOIN afcweb_postmeta AS tabs_1_tab_image ON (afcweb_posts.ID = tabs_1_tab_image.post_id AND tabs_1_tab_image.meta_key='tabs_1_tab_image')
LEFT JOIN afcweb_postmeta AS tabs_2_tab_label ON (afcweb_posts.ID = tabs_2_tab_label.post_id AND tabs_2_tab_label.meta_key='tabs_2_tab_label')
LEFT JOIN afcweb_postmeta AS tabs_2_tab_content ON (afcweb_posts.ID = tabs_2_tab_content.post_id AND tabs_2_tab_content.meta_key='tabs_2_tab_content')
LEFT JOIN afcweb_postmeta AS tabs_2_tab_image ON (afcweb_posts.ID = tabs_2_tab_image.post_id AND tabs_2_tab_image.meta_key='tabs_2_tab_image')
LEFT JOIN afcweb_postmeta AS tabs_3_tab_label ON (afcweb_posts.ID = tabs_3_tab_label.post_id AND tabs_3_tab_label.meta_key='tabs_3_tab_label')
LEFT JOIN afcweb_postmeta AS tabs_3_tab_content ON (afcweb_posts.ID = tabs_3_tab_content.post_id AND tabs_3_tab_content.meta_key='tabs_3_tab_content')
LEFT JOIN afcweb_postmeta AS tabs_3_tab_image ON (afcweb_posts.ID = tabs_3_tab_image.post_id AND tabs_3_tab_image.meta_key='tabs_3_tab_image')
LEFT JOIN afcweb_postmeta AS tabs_4_tab_label ON (afcweb_posts.ID = tabs_4_tab_label.post_id AND tabs_4_tab_label.meta_key='tabs_4_tab_label')
LEFT JOIN afcweb_postmeta AS tabs_4_tab_content ON (afcweb_posts.ID = tabs_4_tab_content.post_id AND tabs_4_tab_content.meta_key='tabs_4_tab_content')
LEFT JOIN afcweb_postmeta AS tabs_4_tab_image ON (afcweb_posts.ID = tabs_4_tab_image.post_id AND tabs_4_tab_image.meta_key='tabs_4_tab_image')
LEFT JOIN afcweb_postmeta AS tabs_5_tab_label ON (afcweb_posts.ID = tabs_5_tab_label.post_id AND tabs_5_tab_label.meta_key='tabs_5_tab_label')
LEFT JOIN afcweb_postmeta AS tabs_5_tab_content ON (afcweb_posts.ID = tabs_5_tab_content.post_id AND tabs_5_tab_content.meta_key='tabs_5_tab_content')
LEFT JOIN afcweb_postmeta AS tabs_5_tab_image ON (afcweb_posts.ID = tabs_5_tab_image.post_id AND tabs_5_tab_image.meta_key='tabs_5_tab_image')
LEFT JOIN afcweb_postmeta AS tabs_6_tab_label ON (afcweb_posts.ID = tabs_6_tab_label.post_id AND tabs_6_tab_label.meta_key='tabs_6_tab_label')
LEFT JOIN afcweb_postmeta AS tabs_6_tab_content ON (afcweb_posts.ID = tabs_6_tab_content.post_id AND tabs_6_tab_content.meta_key='tabs_6_tab_content')
LEFT JOIN afcweb_postmeta AS tabs_6_tab_image ON (afcweb_posts.ID = tabs_6_tab_image.post_id AND tabs_6_tab_image.meta_key='tabs_6_tab_image')
LEFT JOIN afcweb_postmeta AS tabs_7_tab_label ON (afcweb_posts.ID = tabs_7_tab_label.post_id AND tabs_7_tab_label.meta_key='tabs_7_tab_label')
LEFT JOIN afcweb_postmeta AS tabs_7_tab_content ON (afcweb_posts.ID = tabs_7_tab_content.post_id AND tabs_7_tab_content.meta_key='tabs_7_tab_content')
LEFT JOIN afcweb_postmeta AS tabs_7_tab_image ON (afcweb_posts.ID = tabs_7_tab_image.post_id AND tabs_7_tab_image.meta_key='tabs_7_tab_image')
LEFT JOIN afcweb_postmeta AS _thumbnail_id ON (afcweb_posts.ID = _thumbnail_id.post_id AND _thumbnail_id.meta_key='_thumbnail_id')
LEFT JOIN afcweb_postmeta AS _product_image_gallery ON (afcweb_posts.ID = _product_image_gallery.post_id AND _product_image_gallery.meta_key='_product_image_gallery')
LEFT JOIN afcweb_postmeta AS specifications ON (afcweb_posts.ID = specifications.post_id AND specifications.meta_key='specifications')
LEFT JOIN afcweb_postmeta AS submittal_sheet ON (afcweb_posts.ID = submittal_sheet.post_id AND submittal_sheet.meta_key='submittal_sheet')
LEFT JOIN afcweb_postmeta AS file_downloads ON (afcweb_posts.ID = file_downloads.post_id AND file_downloads.meta_key='file_downloads')
LEFT JOIN afcweb_postmeta AS stp ON (afcweb_posts.ID = stp.post_id AND stp.meta_key='stp')
LEFT JOIN afcweb_postmeta AS sat ON (afcweb_posts.ID = sat.post_id AND sat.meta_key='sat')
LEFT JOIN afcweb_postmeta AS dwg ON (afcweb_posts.ID = dwg.post_id AND dwg.meta_key='dwg')
LEFT JOIN afcweb_postmeta AS dxf ON (afcweb_posts.ID = dxf.post_id AND dxf.meta_key='dxf')
WHERE afcweb_posts.post_type = 'product'
AND afcweb_posts.post_status = 'publish'
GROUP BY afcweb_posts.ID ORDER BY afcweb_posts.ID ASC;