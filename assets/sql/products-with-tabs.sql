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
_sku.meta_value AS _sku,
_downloadable.meta_value AS _downloadable,
_virtual.meta_value AS _virtual,
_visibility.meta_value AS _visibility,
_stock.meta_value AS _stock,
_stock_status.meta_value AS _stock_status,
_backorders.meta_value AS _backorders,
_manage_stock.meta_value AS _manage_stock,
_sale_price.meta_value AS _sale_price,
_regular_price.meta_value AS _regular_price,
_weight.meta_value AS _weight,
_length.meta_value AS _length,
_width.meta_value AS _width,
_height.meta_value AS _height,
_tax_status.meta_value AS _tax_status,
_tax_class.meta_value AS _tax_class,
_upsell_ids.meta_value AS _upsell_ids,
_crosssell_ids.meta_value AS _crosssell_ids,
_featured.meta_value AS _featured,
_file_paths.meta_value AS _file_paths
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
LEFT JOIN afcweb_postmeta AS _sku ON (afcweb_posts.ID = _sku.post_id AND _sku.meta_key='_sku')
LEFT JOIN afcweb_postmeta AS _downloadable ON (afcweb_posts.ID = _downloadable.post_id AND _downloadable.meta_key='_downloadable')
LEFT JOIN afcweb_postmeta AS _virtual ON (afcweb_posts.ID = _virtual.post_id AND _virtual.meta_key='_virtual')
LEFT JOIN afcweb_postmeta AS _visibility ON (afcweb_posts.ID = _visibility.post_id AND _visibility.meta_key='_visibility')
LEFT JOIN afcweb_postmeta AS _stock ON (afcweb_posts.ID = _stock.post_id AND _stock.meta_key='_stock')
LEFT JOIN afcweb_postmeta AS _stock_status ON (afcweb_posts.ID = _stock_status.post_id AND _stock_status.meta_key='_stock_status')
LEFT JOIN afcweb_postmeta AS _backorders ON (afcweb_posts.ID = _backorders.post_id AND _backorders.meta_key='_backorders')
LEFT JOIN afcweb_postmeta AS _manage_stock ON (afcweb_posts.ID = _manage_stock.post_id AND _manage_stock.meta_key='_manage_stock')
LEFT JOIN afcweb_postmeta AS _sale_price ON (afcweb_posts.ID = _sale_price.post_id AND _sale_price.meta_key='_sale_price')
LEFT JOIN afcweb_postmeta AS _regular_price ON (afcweb_posts.ID = _regular_price.post_id AND _regular_price.meta_key='_regular_price')
LEFT JOIN afcweb_postmeta AS _weight ON (afcweb_posts.ID = _weight.post_id AND _weight.meta_key='_weight')
LEFT JOIN afcweb_postmeta AS _length ON (afcweb_posts.ID = _length.post_id AND _length.meta_key='_length')
LEFT JOIN afcweb_postmeta AS _width ON (afcweb_posts.ID = _width.post_id AND _width.meta_key='_width')
LEFT JOIN afcweb_postmeta AS _height ON (afcweb_posts.ID = _height.post_id AND _height.meta_key='_height')
LEFT JOIN afcweb_postmeta AS _tax_status ON (afcweb_posts.ID = _tax_status.post_id AND _tax_status.meta_key='_tax_status')
LEFT JOIN afcweb_postmeta AS _tax_class ON (afcweb_posts.ID = _tax_class.post_id AND _tax_class.meta_key='_tax_class')
LEFT JOIN afcweb_postmeta AS _upsell_ids ON (afcweb_posts.ID = _upsell_ids.post_id AND _upsell_ids.meta_key='_upsell_ids')
LEFT JOIN afcweb_postmeta AS _crosssell_ids ON (afcweb_posts.ID = _crosssell_ids.post_id AND _crosssell_ids.meta_key='_crosssell_ids')
LEFT JOIN afcweb_postmeta AS _featured ON (afcweb_posts.ID = _featured.post_id AND _featured.meta_key='_featured')
LEFT JOIN afcweb_postmeta AS _file_paths ON (afcweb_posts.ID = _file_paths.post_id AND _file_paths.meta_key='_file_paths')
WHERE afcweb_posts.post_type = 'product'
AND afcweb_posts.post_status = 'publish'
GROUP BY afcweb_posts.ID ORDER BY afcweb_posts.ID ASC;