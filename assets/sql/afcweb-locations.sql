SELECT afcweb_posts.ID, afcweb_posts.post_title,
company_name.meta_value AS company_name,
cover_afc.meta_value AS cover_afc,
cover_fittings.meta_value AS cover_fittings,
cover_acs.meta_value AS cover_acs,
address.meta_value AS address,
address2.meta_value AS address2,
city.meta_value AS city,
state.meta_value AS state,
zip.meta_value AS zip,
country.meta_value AS country,
toll_free.meta_value AS toll_free,
phone.meta_value AS phone,
fax.meta_value AS fax,
email.meta_value AS email,
lattitude.meta_value AS lattitude,
longitude.meta_value AS longitude,
website.meta_value AS website,
mgr.meta_value AS mgr,
contact_name.meta_value AS contact_name,
contact_email.meta_value AS contact_email,
contact_phone.meta_value AS contact_phone,
FROM afcweb_posts
LEFT JOIN afcweb_postmeta AS company_name ON (afcweb_posts.ID = company_name.post_id AND company_name.meta_key='company_name')
LEFT JOIN afcweb_postmeta AS cover_afc ON (afcweb_posts.ID = cover_afc.post_id AND cover_afc.meta_key='cover_afc')
LEFT JOIN afcweb_postmeta AS cover_fittings ON (afcweb_posts.ID = cover_fittings.post_id AND cover_fittings.meta_key='cover_fittings')
LEFT JOIN afcweb_postmeta AS cover_acs ON (afcweb_posts.ID = cover_acs.post_id AND cover_acs.meta_key='cover_acs')
LEFT JOIN afcweb_postmeta AS address ON (afcweb_posts.ID = address.post_id AND address.meta_key='address')
LEFT JOIN afcweb_postmeta AS address2 ON (afcweb_posts.ID = address2.post_id AND address2.meta_key='address2')
LEFT JOIN afcweb_postmeta AS city ON (afcweb_posts.ID = city.post_id AND city.meta_key='city')
LEFT JOIN afcweb_postmeta AS state ON (afcweb_posts.ID = state.post_id AND state.meta_key='state')
LEFT JOIN afcweb_postmeta AS zip ON (afcweb_posts.ID = zip.post_id AND zip.meta_key='zip')
LEFT JOIN afcweb_postmeta AS country ON (afcweb_posts.ID = country.post_id AND country.meta_key='country')
LEFT JOIN afcweb_postmeta AS toll_free ON (afcweb_posts.ID = toll_free.post_id AND toll_free.meta_key='toll_free')
LEFT JOIN afcweb_postmeta AS phone ON (afcweb_posts.ID = phone.post_id AND phone.meta_key='phone')
LEFT JOIN afcweb_postmeta AS fax ON (afcweb_posts.ID = fax.post_id AND fax.meta_key='fax')
LEFT JOIN afcweb_postmeta AS email ON (afcweb_posts.ID = email.post_id AND email.meta_key='email')
LEFT JOIN afcweb_postmeta AS lattitude ON (afcweb_posts.ID = lattitude.post_id AND lattitude.meta_key='lattitude')
LEFT JOIN afcweb_postmeta AS longitude ON (afcweb_posts.ID = longitude.post_id AND longitude.meta_key='longitude')
LEFT JOIN afcweb_postmeta AS website ON (afcweb_posts.ID = website.post_id AND website.meta_key='website')
LEFT JOIN afcweb_postmeta AS mgr ON (afcweb_posts.ID = mgr.post_id AND mgr.meta_key='mgr')
LEFT JOIN afcweb_postmeta AS contact_name ON (afcweb_posts.ID = contact_name.post_id AND contact_name.meta_key='contact_name')
LEFT JOIN afcweb_postmeta AS contact_email ON (afcweb_posts.ID = contact_email.post_id AND contact_email.meta_key='contact_email')
LEFT JOIN afcweb_postmeta AS contact_phone ON (afcweb_posts.ID = contact_phone.post_id AND contact_phone.meta_key='contact_phone')
WHERE afcweb_posts.post_type = 'atkore_location'
AND afcweb_posts.post_status = 'publish'
GROUP BY afcweb_posts.ID ORDER BY afcweb_posts.ID ASC;